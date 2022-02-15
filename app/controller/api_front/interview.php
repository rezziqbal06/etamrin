<?php
/**
 * API_Front/kandidat
 */
class Interview extends JI_Controller
{
  public $currentDataProgress = 'Interview ';

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_jabatan_model", 'ajm');
    $this->load("api_front/a_pengguna_model", 'apm');
    $this->load("api_front/b_lowongan_model", 'blm');
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/c_apply_model", 'cam');
    $this->load("api_front/c_apply_progress_model", "capm");
    $this->load("api_front/c_interview_model", 'cim');
  }
	private function _sendEmailInterviewRescheduleToKaryawan($ajm,$apm,$bum,$blm,$cam,$cim,$admin_cc=array()){
		$replacer = $this->_emailReplacer();
		$replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
		$replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
		$replacer['interview_utype'] = isset($cim->utype) ? $cim->utype : '';
    $replacer['interview_tempat'] = isset($cim->tempat) ? $cim->tempat : '';
    $replacer['interview_jenis'] = isset($cim->jenis) ? $cim->jenis : '';
    $replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
		$replacer['interview_keterangan'] = isset($cim->keterangan) ? $cim->keterangan : '';
		$replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
		$replacer['interviewer_nama'] = isset($apm->nama) ? $apm->nama : '';
		$replacer['link_profil'] = base_url_admin('pelamar/home/detail/'.$cam->id);
    $replacer['company_name'] = $this->config->semevar->company_name;
		$replacer['app_name'] = $this->config->semevar->app_name;
		$replacer['site_name'] = $this->config->semevar->app_name;
		$replacer['email_dari'] = $this->config->semevar->email_from;
		$this->seme_email->flush();
		$this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
		$this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
		$this->seme_email->subject('Permintaan Reschedule Interview '.$cim->utype.' untuk '.$bum->fnama.' ('.$blm->nama.')');
		$this->seme_email->to($apm->email, $apm->nama);
    if(is_array($admin_cc) && count($admin_cc)){
      foreach($admin_cc as $cc){
        if($cc->email == $apm->email) continue;
        $this->seme_email->cc($cc->email);
      }
    }
		$this->seme_email->template('interview_pelamar_reschedule');
		$this->seme_email->replacer($replacer);
		$this->seme_email->send();
	}
	private function _sendEmailInterviewTidakBerminatToKaryawan($ajm,$apm,$bum,$blm,$cam,$cim,$admin_cc=array()){
		$replacer = $this->_emailReplacer();
		$replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
		$replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
		$replacer['interview_utype'] = isset($cim->utype) ? $cim->utype : '';
    $replacer['interview_tempat'] = isset($cim->tempat) ? $cim->tempat : '';
    $replacer['interview_jenis'] = isset($cim->jenis) ? $cim->jenis : '';
    $replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
		$replacer['interview_keterangan'] = isset($cim->keterangan) ? $cim->keterangan : '';
		$replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
    $replacer['interviewer_nama'] = isset($apm->nama) ? $apm->nama : '';
		$replacer['interview_notes'] = isset($cam->interview_notes) ? $cim->interview_notes : '';
		$replacer['link_profil'] = base_url_admin('pelamar/home/detail/'.$cam->id);
    $replacer['company_name'] = $this->config->semevar->company_name;
		$replacer['app_name'] = $this->config->semevar->app_name;
		$replacer['site_name'] = $this->config->semevar->app_name;
		$replacer['email_dari'] = $this->config->semevar->email_from;
		$this->seme_email->flush();
		$this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
		$this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
		$this->seme_email->subject('Pelamar Tidak Berminat Interview '.$cim->utype.' untuk '.$bum->fnama.' ('.$blm->nama.')');
    if(is_array($admin_cc) && count($admin_cc)){
      $i=0;
      foreach($admin_cc as $cc){
        if($i=0){
          $this->seme_email->to($apm->email);
        }else{
          $this->seme_email->cc($cc->email);
        }
        $i++;
      }
    }else{
      $this->seme_email->to($apm->email, $apm->nama);
    }
		$this->seme_email->template('interview_pelamar_tidak_berminat');
		$this->seme_email->replacer($replacer);
		$this->seme_email->send();
	}

  private function _checkDataProgress($bum, $c_apply_id)
  {
    $progress = array();
    $progress['b_user_id'] = $bum->id;
    $progress['utype'] = 'data';
    $progress['ldate'] = 'NOW()';
    $progress['stepkey'] = $this->currentDataProgress;
    $progress['from_val'] = 0;
    $progress['to_val'] = 1;
    $progress['is_done'] = 0;
    return $progress;
  }

  private function _updateDataProgress($bum,$c_apply_id='')
  {
    $progress = $this->_checkDataProgress($bum, $c_apply_id);
    $counter = count($this->buohm->getByUserId($bum->id));
    if ($counter <= 0) {
      $progress['to_val'] = 1;
      $progress['from_val'] = $progress['to_val'] - 1;
    } else {
      $progress['to_val'] = $counter;
      $progress['from_val'] = $progress['to_val'];
    }
    $bupm = $this->bupm->check($bum->id, $c_apply_id, $this->currentDataProgress, 'data');
    if (isset($bupm->id)) {
      $this->bupm->update($bupm->id, $progress);
    } else {
      $progress['cdate'] = 'NOW()';
      $this->bupm->set($progress);
    }
  }

  public function index()
  {
    $dt = $this->__init();
    $data = array();
    $this->status = 200;
    $this->message = 'Berhasil';
    $c_lamar_id = $this->input->post('c_lamar_id');
    $lamaran = $this->cam->getById($c_lamar_id);
    if (!isset($lamaran->id)) {
      $this->status = 1103;
      $this->message = 'Lamaran tidak ditemukan';
      $this->__json_out($data);
    }
    $data = $this->cim->getByLamarId($c_lamar_id);
    $this->__json_out($data);
  }

  public function baru()
  {

    $dt = $this->__init();
    $data = array();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if (!$c) {
      $this->status = 401;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    $bum = $this->bum->getById($dt['sess']->user->id);
    if (!isset($bum->id)) {
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }

    $tgl = $this->input->post('tgl', '');
    $c_lamar_id = $this->input->post('c_lamar_id', '');
    $utype = $this->input->post('utype', '');

    if (empty(strlen($tgl))) {
      $this->status = 1101;
      $this->message = 'Tanggal harus diisi';
      $this->__json_out($data);
    }

    if (empty(strlen($c_lamar_id))) {
      $this->status = 1102;
      $this->message = 'Lamaran tidak valid';
      $this->__json_out($data);
    }

    $lamaran = $this->cam->getById($c_lamar_id);
    if (!isset($lamaran->id)) {
      $this->status = 1103;
      $this->message = 'Lamaran tidak ditemukan';
      $this->__json_out($data);
    }

    $di = $_POST;
    unset($di['utype']);
    if(!isset($di['hasil_penilaian'])) $di['hasil_penilaian'] = '';
    $di['hasil_penilaian'] = strip_tags($di['hasil_penilaian']);

    if (isset($utype) && $utype == 'hr') {
      $hr = $this->apm->getByJabatan('human resource');
      if (isset($hr->id)) {
        $di['a_pengguna_id'] = $hr->id;
      }
    }
    if (isset($utype)) $di['a_pengguna_utype'] = $utype;

    $res = $this->cim->set($di);
    if ($res) {
      $this->status = 200;
      $this->message = 'Berhasil';
      // $this->_updateDataProgress($dt['sess']->user);
    } else {
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

    $this->__json_out($data);
  }

  public function detail($id)
  {
    $dt = $this->__init();
    $id = (int) $id;
    if ($id <= 0) $id = 0;
    $data = new stdClass();

    $bum = $this->bum->getById($dt['sess']->user->id);
    if (!isset($bum->id)) {
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->cim->getById($id);
    if (!isset($data->id)) {
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }
    $this->__json_out($data);
  }


  public function edit($id)
  {
    $dt = $this->__init();
    $id = (int) $id;
    if ($id <= 0) $id = 0;
    $data = array();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if (!$c) {
      $this->status = 401;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    $bum = $this->bum->getById($dt['sess']->user->id);
    if (!isset($bum->id)) {
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }

    $cim = $this->cim->getById($id);
    if (!isset($cim->id)) {
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }

    $tgl = $this->input->post('tgl', '');
    $c_lamar_id = $this->input->post('c_lamar_id', '');
    $utype = $this->input->post('utype', '');

    if (empty(strlen($tgl))) {
      $this->status = 1101;
      $this->message = 'Tanggal harus diisi';
      $this->__json_out($data);
    }

    if (empty(strlen($c_lamar_id))) {
      $this->status = 1102;
      $this->message = 'Lamaran tidak valid';
      $this->__json_out($data);
    }

    $lamaran = $this->cam->getById($c_lamar_id);
    if (!isset($lamaran->id)) {
      $this->status = 1103;
      $this->message = 'Lamaran tidak ditemukan';
      $this->__json_out($data);
    }

    $di = $_POST;
    unset($di['utype']);

    if (isset($utype) && $utype == 'hr') {
      $hr = $this->apm->getByJabatan('human resource');
      if (isset($hr->id)) {
        $di['a_pengguna_id'] = $hr->id;
      }
    }
    if (isset($utype)) $di['a_pengguna_utype'] = $utype;

    $res = $this->cim->update($id, $di);
    if ($res) {
      $this->status = 200;
      $this->message = 'Berhasil';
      // $this->_updateDataProgress($dt['sess']->user);
    } else {
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

    $this->__json_out($data);
  }
  public function berminat($id){
    $dt = $this->__init();
    if ($id <= 0) $id = 0;
    $data = new stdClass();

    $id = (int) $id;
    $cim = $this->cim->getById($id);
    if (!isset($cim->id)) {
      $this->status = 1131;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $cam = $this->cam->getById($cim->c_apply_id);
    if (!isset($cam->id)) {
      $this->status = 1132;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $bum = $this->bum->getById($cam->b_user_id);
    if (!isset($bum->id)) {
      $this->status = 1133;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    if($dt['sess']->user->id != $bum->id){
      $this->status = 1130;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $blm = $this->blm->getById($cam->b_lowongan_id);
    if (!isset($blm->id)) {
      $this->status = 1128;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $ajm = $this->ajm->getById($blm->a_jabatan_id);
    if (!isset($ajm->id)) {
      $this->status = 1129;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $du = array();
    $du['status_teks'] = INTERVIEW_BERMINAT;
    $res = $this->cim->update($id,$du);
    if ($res) {
      $this->status = 200;
      $this->message = 'Berhasil';

      $this->_setULog($dt['sess']->user->id, 25, $cim->utype);
    } else {
      $this->status = 989;
      $this->message = 'Permintaan tidak dapat dipenuhi';
    }
    $this->__json_out($data);
  }
  public function minta_reschedule($id)
  {
    $dt = $this->__init();
    if ($id <= 0) $id = 0;
    $data = new stdClass();

    $id = (int) $id;
    $cim = $this->cim->getById($id);
    if (!isset($cim->id)) {
      $this->status = 1131;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $cam = $this->cam->getById($cim->c_apply_id);
    if (!isset($cam->id)) {
      $this->status = 1132;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $bum = $this->bum->getById($cam->b_user_id);
    if (!isset($bum->id)) {
      $this->status = 1133;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    if($dt['sess']->user->id != $bum->id){
      $this->status = 1130;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $blm = $this->blm->getById($cam->b_lowongan_id);
    if (!isset($blm->id)) {
      $this->status = 1128;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $ajm = $this->ajm->getById($blm->a_jabatan_id);
    if (!isset($ajm->id)) {
      $this->status = 1129;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $du = array();
    $du['status_no'] = -1;
    $du['status_teks'] = INTERVIEW_DIRESCHEDULE_PELAMAR;
    $res = $this->cim->update($id,$du);
    if ($res) {
      $this->status = 200;
      $this->message = 'Berhasil';

      $apm = $this->apm->getById($cim->a_pengguna_id1);
      if(isset($apm->id)){
        $this->_sendEmailInterviewRescheduleToKaryawan($ajm,$apm,$bum,$blm,$cam,$cim,$this->apm->getIsNotifInterview());
      }
      if(!is_null($cim->a_pengguna_id2)){
        $apm = $this->apm->getById($cim->a_pengguna_id2);
        if(isset($apm->id)){
          $this->_sendEmailInterviewRescheduleToKaryawan($ajm,$apm,$bum,$blm,$cam,$cim);
        }
      }
      $this->_setULog($dt['sess']->user->id, 26, $cim->utype);
    } else {
      $this->status = 989;
      $this->message = 'Permintaan tidak dapat dipenuhi';
    }
    $this->__json_out($data);
  }

  public function tidak_berminat($id)
  {
    $dt = $this->__init();
    if ($id <= 0) $id = 0;
    $data = new stdClass();

    $id = (int) $id;
    $cim = $this->cim->getById($id);
    if (!isset($cim->id)) {
      $this->status = 1135;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $cam = $this->cam->getById($cim->c_apply_id);
    if (!isset($cam->id)) {
      $this->status = 1136;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $bum = $this->bum->getById($cam->b_user_id);
    if (!isset($bum->id)) {
      $this->status = 1137;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    if($dt['sess']->user->id != $bum->id){
      $this->status = 1138;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $blm = $this->blm->getById($cam->b_lowongan_id);
    if (!isset($blm->id)) {
      $this->status = 1139;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }
    $ajm = $this->ajm->getById($blm->a_jabatan_id);
    if (!isset($ajm->id)) {
      $this->status = 1140;
      $this->message = 'This action not belong to this user';
      $this->__json_out($data);
      die();
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $du = array();
    $du['status_no'] = 9;
    $du['status_teks'] = INTERVIEW_TIDAK_BERMINAT;
    $res = $this->cim->update($id,$du);
    if ($res) {
      $this->status = 200;
      $this->message = 'Berhasil';

      $interview_notes = trim( strip_tags( $this->input->post( 'interview_notes', '') ) );
      $edate = date('Y-m-d 23:59:59',strtotime('+180 days'));
      $this->cam->update($cam->id, array('edate'=>$edate,'interview_notes'=>$interview_notes));
      $this->bum->update($bum->id, array('apply_statno'=>8));

      $apm = $this->apm->getById($cim->a_pengguna_id1);
      if(isset($apm->id)){
        $this->_sendEmailInterviewTidakBerminatToKaryawan($ajm,$apm,$bum,$blm,$cam,$cim,$this->apm->getIsNotifInterview());
      }
      if(!is_null($cim->a_pengguna_id2)){
        $apm = $this->apm->getById($cim->a_pengguna_id2);
        if(isset($apm->id)){
          $this->_sendEmailInterviewTidakBerminatToKaryawan($ajm,$apm,$bum,$blm,$cam,$cim);
        }
      }
      $this->_setULog($dt['sess']->user->id, 27, $cim->utype);
    } else {
      $this->status = 989;
      $this->message = 'Perubahan gagal diterapkan';
    }
    $this->__json_out($data);
  }
}
