<?php
/**
* Interview
*/
class Mail_Preview extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_email");
    $this->load("front/a_jabatan_model", "ajm");
    $this->load("front/a_pengguna_model", "apm");
    $this->load("front/b_lowongan_model", "blm");
    $this->load("front/b_user_model", "bum");
    $this->load("front/c_interview_model", "cim");
    $this->load("front/c_apply_model", "cam");
    $this->current_menu = 'interview_hr';
  }

  private function _sendEmailInterviewUndanganToPelamar($ajm,$bum,$blm,$cam,$cim){
    $replacer = $this->_emailReplacer();
    $replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
    $replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
    $replacer['interview_utype'] = isset($cim->utype) ? $cim->utype : '';
    $replacer['interview_jenis'] = isset($cim->jenis) ? $cim->jenis : '';
    $replacer['interview_tempat'] = isset($cim->tempat) ? $cim->tempat : '';
    $replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
    $replacer['interview_keterangan'] = isset($cim->keterangan) ? $cim->keterangan : '';
    $replacer['interview_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal');
    $replacer['interview_waktu'] = $this->__dateIndonesia($cim->tglwaktu,'jam');
    $replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
    $replacer['email_dari'] = $this->config->semevar->email_from;
    $replacer['link'] = base_url('kandidat/interview/');
    $replacer['company_nama'] = $this->config->semevar->app_name;
    $replacer['site_name'] = $this->config->semevar->app_name;
    $replacer['email_dari'] = $this->config->semevar->email_from;
    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->app_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Undangan Interview untuk '.$bum->fnama.' ('.$blm->nama.')');
    $this->seme_email->to($bum->email, $bum->fnama);
    $this->seme_email->template('interview_undangan_pelamar');
    $this->seme_email->replacer($replacer);
    $this->seme_email->preview();
  }
  private function _sendEmailInterviewUndanganToKaryawan($ajm,$apm,$bum,$blm,$cam,$cim,$token_utype,$token){
    $replacer = $this->_emailReplacer();
    $replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
    $replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
    $replacer['interviewer_nama'] = isset($apm->nama) ? $apm->nama : '';
    $replacer['link_profil'] = base_url_admin('pelamar/home/detail/'.$cam->id);
		$replacer['link_form'] = base_url('interview/form/user/'.$token_utype.'/'.$token);
    $replacer['interview_utype'] = isset($cim->utype) ? $cim->utype : '';
		$replacer['interview_jenis'] = isset($cim->jenis) ? $cim->jenis : '';
		$replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
		$replacer['interview_tempat'] = isset($cim->tempat) ? $cim->tempat : '';
		$replacer['interview_keterangan'] = isset($cim->keterangan) ? $cim->keterangan : '';
		$replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Jadwal Interview '.$cim->utype.' Terkonfirmasi untuk '.$bum->fnama.' ('.$blm->nama.')');
    $this->seme_email->to($apm->email, $apm->nama);
    $this->seme_email->template('interview_undangan_karyawan');
    $this->seme_email->replacer($replacer);
    $this->seme_email->preview();
  }
  private function _sendEmailInterviewUnApprovedToUser($ajm,$apm,$bum,$blm,$cam,$cim){
    $replacer = $this->_emailReplacer();
    $replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
    $replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
    $replacer['interview_utype'] = isset($cim->utype) ? $cim->utype : '';
    $replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
    $replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
    $replacer['interviewer_nama'] = isset($apm->nama) ? $apm->nama : '';
    $replacer['link_profil'] = base_url_admin('pelamar/home/detail/'.$cam->id);
    $replacer['company_nama'] = $this->config->semevar->company_name;
    $replacer['site_name'] = $this->config->semevar->site_name;
    $replacer['email_dari'] = $this->config->semevar->email_from;
    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Jadwal Interview '.$cim->utye.' Dibatalkan / Reschedule untuk '.$bum->fnama.' ('.$blm->nama.')');
    $this->seme_email->to($apm->email, $apm->nama);
    $this->seme_email->template('interview_unapproved_karyawan');
    $this->seme_email->replacer($replacer);
    $this->seme_email->preview();
  }

  private function _sendEmailInterviewConfirmationToUser($ajm,$apm,$bum,$blm,$cam,$cim,$token){
    $replacer = $this->_emailReplacer();
    $replacer['pelamar_nama'] = $bum->fnama;
    $replacer['pelamar_posisi'] = $ajm->nama;
    $replacer['interview_utype'] = $cim->utype;
    $replacer['interviewer_nama'] = $apm->nama;
    $replacer['interview_lokasi'] = $cim->lokasi;
    $replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
    $replacer['link_profil'] = base_url_admin('pelamar/home/index/'.$cam->id);
    $replacer['link_approve'] = base_url('interview/user/approve/'.$token);
    $replacer['link_unapprove'] = base_url('interview/user/disapprove/'.$token);
    $replacer['company_nama'] = $this->config->semevar->company_name;
    $replacer['site_name'] = $this->config->semevar->site_name;
    $replacer['email_dari'] = $this->config->semevar->email_from;
    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Konfirmasi Jadwal Interview '.$cim->utype.' untuk '.$bum->fnama.' ('.$ajm->nama.')');
    $this->seme_email->to($apm->email, $apm->nama);
    $this->seme_email->template('interview_konfirmasi_karyawan');
    $this->seme_email->replacer($replacer);
    $this->seme_email->preview();
  }

  private function _sendEmailRejectionToPelamar($ajm,$bum){
    $replacer = $this->_emailReplacer();
		$replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
		$replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
		$replacer['company_nama'] = $this->config->semevar->company_name;
    $replacer['site_name'] = $this->config->semevar->site_name;
		$replacer['email_dari'] = $this->config->semevar->email_from;
    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Mohon Maaf anda Tidak Lolos untuk posisi ('.(isset($ajm->nama) ? $ajm->nama : '').')');
    $this->seme_email->to($bum->email, $bum->fnama);
    $this->seme_email->template('pelamar_reject');
    $this->seme_email->replacer($replacer);
    $this->seme_email->preview();
  }

  public function index()
  {
    $data = $this->__init();
    $this->debug($this->config->semevar);
  }
  public function interview_undangan_karyawan($cim_id=''){
    $apm1 = $apm2 = $ajm = $cam = $blm = $bum = new stdClass();
    $cim = $this->cim->getById($cim_id);
    if(isset($cim->c_apply_id)) $cam = $this->cam->getById($cim->c_apply_id);
    if(isset($cam->b_lowongan_id)) $blm = $this->blm->getById($cam->b_lowongan_id);
    if(isset($cam->b_user_id)) $bum = $this->bum->getById($cam->b_user_id);
    if(isset($blm->a_jabatan_id)) $ajm = $this->ajm->getById($blm->a_jabatan_id);
    $apm1 = $this->apm->getById($cim->a_pengguna_id1);
    if(!is_null($cim->a_pengguna_id2)){
      $apm2 = $this->apm->getById($cim->a_pengguna_id2);
    }
    $this->_sendEmailInterviewUndanganToKaryawan($ajm,$apm1,$bum,$blm,$cam,$cim,1,$cim->a_pengguna_id1_token);
    if(isset($apm2->id)){
      echo '<hr>';
      $this->_sendEmailInterviewUndanganToKaryawan($ajm,$apm2,$bum,$blm,$cam,$cim,2,$cim->a_pengguna_id2_token);
      echo '<hr>';
    }
  }
  public function interview_undangan_pelamar($cim_id=''){
    $apm1 = $apm2 = $ajm = $cam = $blm = $bum = new stdClass();
    $cim = $this->cim->getById($cim_id);
    if(isset($cim->c_apply_id)) $cam = $this->cam->getById($cim->c_apply_id);
    if(isset($cam->b_lowongan_id)) $blm = $this->blm->getById($cam->b_lowongan_id);
    if(isset($cam->b_user_id)) $bum = $this->bum->getById($cam->b_user_id);
    if(isset($blm->a_jabatan_id)) $ajm = $this->ajm->getById($blm->a_jabatan_id);
    $apm1 = $this->apm->getById($cim->a_pengguna_id1);
    if(!is_null($cim->a_pengguna_id2)){
      $apm2 = $this->apm->getById($cim->a_pengguna_id2);
    }
    $this->_sendEmailInterviewUndanganToPelamar($ajm,$bum,$blm,$cam,$cim);
  }
  public function pelamar_reject($cam_id=''){
    $cam = $this->cam->getById($cam_id);
    $bum = $this->bum->getById($cam->b_user_id);
    $blm = $this->blm->getById($cam->b_lowongan_id);
    $ajm = $this->blm->getById($blm->a_jabatan_id);
    $this->_sendEmailRejectionToPelamar($ajm,$bum);
  }
  public function verifikasi_email($bum_id=''){
    $replacer = $this->_emailReplacer();
    $replacer['site_logo'] = $this->cdn_url($this->config->semevar->site_logo);
    $replacer['app_name'] = $this->config->semevar->app_name;
    $replacer['company_name'] = $this->config->semevar->app_name;
    $replacer['site_name'] = $this->config->semevar->app_name;
    $replacer['email_reply'] = $this->config->semevar->email_reply;

    $replacer['fnama'] = 'Daeng Rosanda';
    $replacer['activation_code'] = 'ABCD00';
    $email = 'daengrosanda@gmail.com';
    $nama = 'Daeng Rosanda';

    $bum = $this->bum->getById($bum_id);
    if(isset($bum->fnama)) $replacer['fnama'] = $bum->fnama;
    if(isset($bum->api_reg_token)) $replacer['activation_code'] = $bum->api_reg_token;
    if(isset($bum->email)) $email = $bum->email;
    if(isset($bum->nama)) $nama = $bum->fnama;

    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->app_name, $this->config->semevar->app_name);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Kode untuk Verifikasi Email Pendaftaran Calon Karyawan '.$this->config->semevar->app_name);
    $this->seme_email->to($email, $nama);
    $this->seme_email->template('user_email_verification');
    $this->seme_email->replacer($replacer);
    $this->seme_email->preview();
  }

  public function lupa_password($bum_id=''){
    $replacer = $this->_emailReplacer();
    $replacer['site_logo'] = $this->cdn_url($this->config->semevar->site_logo);
    $replacer['app_name'] = $this->config->semevar->app_name;
    $replacer['company_name'] = $this->config->semevar->app_name;
    $replacer['site_name'] = $this->config->semevar->app_name;
    $replacer['email_reply'] = $this->config->semevar->email_reply;

    $replacer['fnama'] = 'Daeng Rosanda';
    $replacer['activation_code'] = 'ABCD00';
    $email = 'daengrosanda@gmail.com';
    $nama = 'Daeng Rosanda';

    $bum = $this->bum->getById($bum_id);
    if(isset($bum->fnama)) $replacer['fnama'] = $bum->fnama;
    if(isset($bum->api_reg_token)) $replacer['activation_code'] = $bum->api_reg_token;
    if(isset($bum->email)) $email = $bum->email;
    if(isset($bum->nama)) $nama = $bum->fnama;

    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->app_name, $this->config->semevar->app_name);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Permintaan Link untuk Reset Password Calon Karyawan '.$this->config->semevar->app_name);
    $this->seme_email->to($email, $nama);
    $this->seme_email->template('user_password_lupa');
    $this->seme_email->replacer($replacer);
    $this->seme_email->preview();
  }
}
