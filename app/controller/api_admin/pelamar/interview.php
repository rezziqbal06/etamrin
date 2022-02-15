<?php
class Interview extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->lib("conumtext");
		$this->lib("seme_email");
		$this->lib("seme_purifier");
		$this->load("api_admin/a_jabatan_model",'ajm');
		$this->load("api_admin/a_pengguna_model",'apm');
		$this->load("api_admin/b_user_model",'bum');
		$this->load("api_admin/b_lowongan_model",'blm');
		$this->load("api_admin/c_apply_model",'cam');
		$this->load("api_admin/c_apply_sessiontes_model",'castm');
		$this->load("api_admin/c_interview_model",'cim');
	}
	public function _sendEmailInterviewUndanganToPelamar($ajm,$bum,$blm,$cam,$cim,$is_reschedule=0){
		$replacer = $this->_emailReplacer();
		$replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
		$replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
		$replacer['interview_utype'] = isset($cim->utype) ? $cim->utype : '';
		if($is_reschedule){
			$replacer['interview_utype'] .= ' (Reschedule)';
		}
		$replacer['interview_jenis'] = isset($cim->jenis) ? $cim->jenis : 'Online';
		$replacer['interview_tempat'] = isset($cim->tempat) ? $cim->tempat : '????';
		$replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
		$replacer['interview_keterangan'] = isset($cim->keterangan) ? $cim->keterangan : '';
		$replacer['interview_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal');
		$replacer['interview_waktu'] = $this->__dateIndonesia($cim->tglwaktu,'jam');
		$replacer['site_name'] = $this->config->semevar->app_name;
		$replacer['email_dari'] = $this->config->semevar->email_from;
		$replacer['link'] = base_url('kandidat/interview/');
		$replacer['app_name'] = $this->config->semevar->app_name;
		$replacer['company_name'] = $this->config->semevar->app_name;
		$replacer['site_name'] = $this->config->semevar->app_name;
		$replacer['email_dari'] = $this->config->semevar->email_from;
		$this->seme_email->flush();
		$this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
		$this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
		$this->seme_email->subject('Undangan Interview '.(isset($cim->utype) ? $cim->utype : '').' '.$this->config->semevar->app_name.'');
		$this->seme_email->to($bum->email, $bum->fnama);
		if($cim->utype == 'HR'){
			$this->seme_email->template('interview_undangan_pelamar');
		}else{
			$this->seme_email->template('interview_user_undangan_pelamar');
		}
		$this->seme_email->replacer($replacer);
		$this->seme_email->send();
	}
	private function _sendEmailInterviewUndanganToKarayawan($ajm,$apm,$bum,$blm,$cam,$cim,$token_utype,$token,$is_reschedule=0){
		$replacer = $this->_emailReplacer();
		$replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
		$replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
		$replacer['interview_utype'] = isset($cim->utype) ? $cim->utype : '';
		if($is_reschedule){
			$replacer['interview_utype'] .= ' (Reschedule)';
		}
		$replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
		$replacer['interviewer_nama'] = isset($apm->nama) ? $apm->nama : '';
		$replacer['link_profil'] = base_url_admin('pelamar/home/detail/'.$cam->id);
		$replacer['link_form'] = base_url('interview/form/user/'.$token_utype.'/'.$token);
		$replacer['app_name'] = $this->config->semevar->app_name;
		$replacer['company_name'] = $this->config->semevar->app_name;
		$replacer['site_name'] = $this->config->semevar->app_name;
		$replacer['email_dari'] = $this->config->semevar->email_from;
		$replacer['interview_jenis'] = isset($cim->jenis) ? $cim->jenis : '';
		$replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
		$replacer['interview_tempat'] = isset($cim->tempat) ? $cim->tempat : '';
		$replacer['interview_keterangan'] = isset($cim->keterangan) ? $cim->keterangan : '';
		$this->seme_email->flush();
		$this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
		$this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
		$this->seme_email->subject('Jadwal Interview '.$cim->utype.' untuk '.$bum->fnama.' ('.$blm->nama.')');
		$this->seme_email->to($apm->email, $apm->nama);
		$this->seme_email->template('interview_undangan_karyawan');
		$this->seme_email->replacer($replacer);
		$this->seme_email->send();
	}

  private function _sendEmailInterviewConfirmationToUser($ajm,$apm,$bum,$blm,$cam,$cim,$token){
    $replacer = $this->_emailReplacer();
		$replacer['pelamar_nama'] = $bum->fnama;
		$replacer['pelamar_posisi'] = $ajm->nama;
		$replacer['interview_utype'] = $cim->utype;
    $replacer['interviewer_nama'] = $apm->nama;
		$replacer['interview_waktu_tanggal'] = $this->__dateIndonesia($cim->tglwaktu,'hari_tanggal_jam');
		$replacer['link_profil'] = base_url_admin('pelamar/home/detail/'.$cam->id);
		$replacer['link_approve'] = base_url('interview/user/approve/'.$token);
		$replacer['link_unapprove'] = base_url('interview/user/disapprove/'.$token);
		$replacer['interview_jenis'] = isset($cim->jenis) ? $cim->jenis : '';
		$replacer['interview_lokasi'] = isset($cim->lokasi) ? $cim->lokasi : '';
		$replacer['interview_tempat'] = isset($cim->tempat) ? $cim->tempat : '';
		$replacer['interview_keterangan'] = isset($cim->keterangan) ? $cim->keterangan : '';
		$this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Konfirmasi Jadwal Interview '.$cim->utype.' untuk '.$bum->fnama.' ('.$ajm->nama.')');
    $this->seme_email->to($apm->email, $apm->nama);
    $this->seme_email->template('interview_konfirmasi_karyawan');
    $this->seme_email->replacer($replacer);
    $this->seme_email->send();
  }

	public function index($c_apply_id=''){
		$d = $this->__init();
		$data = array();
		$c_apply_id = (int) $c_apply_id;
		if($c_apply_id<=0) $c_apply_id = 0;

		$draw = $this->input->post("draw");
		$sval = $this->input->post("search");
		$sSearch = $this->input->post("sSearch",'');
		$sEcho = $this->input->post("sEcho");
		$page = $this->input->post("iDisplayStart");
		$pagesize = $this->input->post("iDisplayLength");

		$iSortCol_0 = $this->input->post("iSortCol_0");
		$sSortDir_0 = $this->input->post("sSortDir_0");


		$sortDir = strtoupper($sSortDir_0);
		if(empty($sortDir)) $sortDir = "DESC";
		if(strtolower($sortDir) != "desc"){
			$sortDir = "ASC";
		}

		//get table alias
		$tbl_as = $this->cim->getTblAs();

		//sorting logic
		switch($iSortCol_0){
			case 0:
				$sortCol = "$tbl_as.id";
				break;
			default:
				$sortCol = "$tbl_as.id";
		}

		if(empty($draw)) $draw = 0;
		if(empty($pagesize)) $pagesize=10;
		if(empty($page)) $page=0;

		$keyword = $sSearch;

		//advanced search / filter
		$utype = $this->input->post("utype");
		if(empty($utype)) $utype = "";
		$badan_hukum = $this->input->post("badan_hukum");
		if(empty($badan_hukum)) $badan_hukum = "";
		$is_vendor = $this->input->post("is_vendor");
		$is_active = $this->input->post("is_active");

		$this->status = 200;
		$this->message = 'Berhasil';

		$dcount = $this->cim->countAll($keyword,$c_apply_id,'1');
		$ddata = $this->cim->getAll($page,$pagesize,$sortCol,$sortDir,$keyword,$c_apply_id,'1');
		foreach($ddata as &$gd){
			if(isset($gd->lokasi) && isset($gd->jenis) && isset($gd->tempat) && isset($gd->keterangan)){
				$lokasi = '';
				$lokasi .= "[$gd->jenis]<br>";
				if($gd->jenis == 'Online'){
					$lokasi .= "<b>$gd->tempat</b><br>";
					$lokasi .= "<a href=\"$gd->lokasi\">$gd->lokasi</a>";
					if(strlen($gd->keterangan)){
						$lokasi .= "<br><small>$gd->keterangan</small>";
					}
				}else{
					$lokasi .= "<b>$gd->tempat</b><br>";
					$lokasi .= "<u>$gd->lokasi</u>";
					if(strlen($gd->keterangan)){
						$lokasi .= "<br><small>$gd->keterangan</small>";
					}
				}
				$gd->lokasi = $lokasi;
			}
			if(isset($gd->tglwaktu)){
				$gd->tglwaktu = $this->__dateIndonesia($gd->tglwaktu,'hari_tanggal_jam');
			}
		}
		$this->__jsonDataTable($ddata,$dcount);
	}
	public function get_data(){
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$draw = $this->input->post("draw");
		$sval = $this->input->post("search");
		$sSearch = $this->input->post("sSearch");
		$sEcho = $this->input->post("sEcho");
		$page = $this->input->post("iDisplayStart");
		$pagesize = $this->input->post("iDisplayLength");

		$iSortCol_0 = $this->input->post("iSortCol_0");
		$sSortDir_0 = $this->input->post("sSortDir_0");


		$sortCol = "date";
		$sortDir = strtoupper($sSortDir_0);
		if(empty($sortDir)) $sortDir = "DESC";
		if(strtolower($sortDir) != "desc"){
			$sortDir = "ASC";
		}



		if(empty($draw)) $draw = 0;
		if(empty($pagesize)) $pagesize=10;
		if(empty($page)) $page=0;

		$keyword = $sSearch;

		$this->status = 200;
		$this->message = 'Berhasil';
		$dcount = $this->cim->countAll($page,$pagesize,$keyword);
		$ddata = $this->cim->getAll($page,$pagesize,$keyword);

		foreach($ddata as &$gd){
			if(isset($gd->is_active)){
				if(!empty($gd->is_active)){
					$gd->is_active = 'Aktif';
				}else{
					$gd->is_active = 'Tidak Aktif';
				}
			}
		}

		$data['cabang'] = $ddata;
		//sleep(3);
		$another = array();
		$this->__json_out($ddata);
	}
	public function baru(){
		$d = $this->__init();

		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$di = array();
	 	$di['c_apply_id'] = (int) $this->input->post('c_apply_id','0');
		if($di['c_apply_id']<=0){
			$this->status = 1041;
			$this->message = 'Invalid apply ID';
			$this->__json_out($data);
			die();
		}
		$cam = $this->cam->getById($di['c_apply_id']);
		if(!isset($cam->id)){
			$this->status = 1030;
			$this->message = 'Data Apply not found';
			$this->__json_out($data);
			die();
		}

		$blm = $this->blm->getById($cam->b_lowongan_id);
		if(!isset($blm->id)){
			$this->status = 1031;
			$this->message = 'Data Lowongan not found';
			$this->__json_out($data);
			die();
		}
		$ajm = $this->ajm->getById($blm->a_jabatan_id);
		if(!isset($ajm->id)){
			$this->status = 1032;
			$this->message = 'Data Jabatan not found';
			$this->__json_out($data);
			die();
		}

		$bum = $this->bum->getById($cam->b_user_id);
		if(!isset($bum->id)){
			$this->status = 1033;
			$this->message = 'Data Pelamar not found (UID: '.$cam->b_user_id.')';
			$this->__json_out($data);
			die();
		}
		if(intval($bum->apply_statno) <=3){
			$udah_tes = 0;
			$castm = $this->castm->getByApplyId($cam->id);
			if(is_array($castm) && count($castm)){
				foreach($castm as $c){
					if($c->utype == 'kepribadian' && !empty($c->is_done)){
						$udah_tes++;
					}elseif($c->utype == 'iq' && !empty($c->is_done)){
						$udah_tes++;
					}
				}
			}
			if($udah_tes == 0){
				$this->status = 1088;
				$this->message = 'Tidak dapat membuat jadwal interview karena Pelamar belum menyelesaikan Psikotest';
				$this->__json_out($data);
				die();
			}elseif($udah_tes == 2){
				$this->bum->update($bum->id, array('apply_statno'=>4));
			}
		}

		$apm1 = new stdClass();
		$apm2 = new stdClass();

		$di['utype'] = $this->input->post('utype','');
		if(!in_array($di['utype'], array('HR','User'))) {
			$this->status = 1042;
			$this->message = 'Jenis interview harus diisi';
			$this->__json_out($data);
			die();
		}

		$cim = $this->cim->check($di['c_apply_id'],$di['utype']);
		if(isset($cim->id)){
			$this->status = 1040;
			$this->message = 'Interview '.$di['utype'].' sudah ada, silakan batalkan interview sebelumnya terlebih dahulu.';
			$this->__json_out($data);
			die();
		}


		$a_pengguna_id1 = 'null';
		$a_pengguna_id2 = 'null';
		$a_pengguna1_nama = $this->input->post('a_pengguna_id1_nama','');
		$a_pengguna1_email = $this->input->post('a_pengguna_id1_email','');
		$a_pengguna1_jabatan_id = (int) $this->input->post('a_pengguna_id1_jabatan_id','');

		if($a_pengguna1_jabatan_id<=0){
			$this->status = 1050;
			$this->message = 'Silakan pilih jabatan interviewer 1';
			$this->__json_out($data);
			die();
		}
		if(strlen($a_pengguna1_email)<=4){
			$this->status = 1051;
			$this->message = 'Silakan isi email untuk interviewer 1';
			$this->__json_out($data);
			die();
		}
		if(strlen($a_pengguna1_nama)<=1){
			$this->status = 1052;
			$this->message = 'Silakan isi nama untuk interviewer 1';
			$this->__json_out($data);
			die();
		}

		$apm1 = $this->apm->check($a_pengguna1_jabatan_id,$a_pengguna1_email);
		if(isset($apm1->id)){
			$a_pengguna_id1 = $apm1->id;
			$this->apm->update($apm1->id,array('nama'=>$a_pengguna1_nama));
		}else{
			$a_pengguna_id1 = $this->apm->set(array('is_notif_interview'=>0, 'utype'=>'karyawan','a_jabatan_id'=>$a_pengguna1_jabatan_id,'email'=>$a_pengguna1_email,'nama'=>$a_pengguna1_nama,'username'=>$a_pengguna1_jabatan_id.'-'.$a_pengguna1_email,'foto'=>'','password'=>md5(rand(0,999))));
			$apm1 = $this->apm->getById($a_pengguna_id1);
		}

		if($di['utype'] == 'User'){
			$a_pengguna_id2_jabatan_id = (int) $this->input->post('a_pengguna_id2_jabatan_id','');
			if($a_pengguna_id2_jabatan_id>0){
				$a_pengguna2_email = $this->input->post('a_pengguna_id2_email','');
				if(strlen($a_pengguna1_email)<=4){
					$this->status = 1053;
					$this->message = 'Silakan isi email untuk interviewer 2';
					$this->__json_out($data);
					die();
				}

				$a_pengguna2_nama = $this->input->post('a_pengguna_id2_nama','');
				if(strlen($a_pengguna2_nama)<=1){
					$this->status = 1054;
					$this->message = 'Silakan isi nama untuk interviewer 2';
					$this->__json_out($data);
					die();
				}
				$apm2 = $this->apm->check($a_pengguna_id2_jabatan_id,$a_pengguna2_email);
				if(isset($apm2->id)){
					$a_pengguna_id2 = $apm2->id;
					$this->apm->update($apm2->id,array('nama'=>$a_pengguna2_nama));
				}else{
					$a_pengguna_id2 = $this->apm->set(array('is_notif_interview'=>0, 'utype'=>'karyawan','a_jabatan_id'=>$a_pengguna_id2_jabatan_id,'email'=>$a_pengguna2_email,'nama'=>$a_pengguna2_nama,'username'=>$a_pengguna2_email,'foto'=>'','password'=>sha1(rand(0,999))));
					$apm2 = $this->apm->getById($a_pengguna_id2);
				}
			}
		}

		$di['a_pengguna_id1'] = $a_pengguna_id1;
		$di['a_pengguna_id2'] = $a_pengguna_id2;
		$di['tglwaktu'] = $this->input->post('tglwaktu', date('Y-m-d 10:00:00',strtotime('+1 day')));
		$di['cdate'] = 'NOW()';
		$di['status_no'] = '4';
		$di['is_active'] = '1';
		$di['status_teks'] = INTERVIEW_DIJADWALKAN;
		$di['a_pengguna_id1_token'] = $this->conumtext->genRand('alpha',12,16);
		$di['a_pengguna_id2_token'] = 'NULL';
		$di['tempat'] = trim(strip_tags($this->input->post('tempat')));
		$di['keterangan'] = trim(strip_tags($this->input->post('keterangan')));
		$di['lokasi'] = trim(strip_tags($this->input->post('lokasi')));
		$di['jenis'] = trim(strip_tags($this->input->post('jenis')));
		if(isset($apm2->id)){
			$di['a_pengguna_id2_token'] = $this->conumtext->genRand('alpha',12,16);
		}

		//start transaction
		$this->cim->trans_start();

		//insert into db
		$res = $this->cim->set($di);
		if($res){
			$this->cim->trans_commit();
			$this->status = 200;
			$this->message = 'Data baru berhasil ditambahkan';

			$cim = $this->cim->getById($res);
			$this->bum->update($bum->id, array('apply_statno'=>5));

			$this->_sendEmailInterviewUndanganToPelamar($ajm,$bum,$blm,$cam,$cim);
			if(isset($apm1->id)){
				$this->_sendEmailInterviewUndanganToKarayawan($ajm,$apm1,$bum,$blm,$cam,$cim,1,$di['a_pengguna_id1_token']);
			}

			if(isset($apm2->id)){
				$this->_sendEmailInterviewUndanganToKarayawan($ajm,$apm2,$bum,$blm,$cam,$cim,2,$di['a_pengguna_id2_token']);
			}
		}else{
			$this->status = 110;
			$this->message = 'Gagal menyisipkan cabang ke basis data';
			$this->cim->trans_rollback();
		}
		$this->cim->trans_end();
		$this->__json_out($data);
	}
	public function detail($id){
		$id = (int) $id;
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login && empty($id)){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$this->status = 200;
		$this->message = 'Berhasil';
		$data = $this->cim->getById($id);
		if(!isset($data->id)){
			$data = new stdClass();
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}
		$apm1 = $this->apm->getById($data->a_pengguna_id1);
		$apm2 = new stdClass();
		if(!is_null($data->a_pengguna_id2)){
			$apm2 = $this->apm->getById($data->a_pengguna_id2);
		}

		$data->a_pengguna_id1_email = isset($apm1->email) ? $apm1->email : '';
		$data->a_pengguna_id2_email = isset($apm2->email) ? $apm2->email : '';
		$data->a_pengguna_id1_nama = isset($apm1->nama) ? $apm1->nama : '';
		$data->a_pengguna_id2_nama = isset($apm2->nama) ? $apm2->nama : '';
		$data->a_pengguna_id1_jabatan_id = isset($apm1->a_jabatan_id) ? $apm1->a_jabatan_id : '';
		$data->a_pengguna_id2_jabatan_id = isset($apm2->a_jabatan_id) ? $apm2->a_jabatan_id : '';

		$data->tanggal = date('Y-m-d',strtotime($data->tglwaktu));
		$data->waktu = date('G:i:00',strtotime($data->tglwaktu));
		$data->waktu_jam = date('H', strtotime($data->tglwaktu));
		$data->waktu_menit = date('i', strtotime($data->tglwaktu));

		$this->__json_out($data);
	}
	public function edit($id){
		$d = $this->__init();
		$data = array();

		$id = (int) $id;
		if($id<=0){
			$this->status = 444;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}
		$cim = $this->cim->getById($id);
		if(!isset($cim->id)){
			$this->status = 945;
			$this->message = 'Data interview with supplied ID not found';
			$this->__json_out($data);
			return;
		}
		$cam = $this->cam->getById($cim->c_apply_id);
		if(!isset($cam->id)){
			$this->status = 946;
			$this->message = 'Data apply with supplied ID not found';
			$this->__json_out($data);
			return;
		}
		$bum = $this->bum->getById($cam->b_user_id);
		if(!isset($bum->id)){
			$this->status = 947;
			$this->message = 'Data user with supplied ID not found';
			$this->__json_out($data);
			return;
		}
		$blm = $this->blm->getById($cam->b_lowongan_id);
		if(!isset($blm->id)){
			$this->status = 948;
			$this->message = 'Data lowongan with supplied ID not found';
			$this->__json_out($data);
			return;
		}
		$ajm = $this->ajm->getById($blm->a_jabatan_id);
		if(!isset($blm->id)){
			$this->status = 949;
			$this->message = 'Data jabatan with supplied ID not found';
			$this->__json_out($data);
			return;
		}

		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$a_pengguna1_jabatan_id = (int) $this->input->post('a_pengguna_id1_jabatan_id','0');
		$a_pengguna1_email = $this->input->post('a_pengguna_id1_email','');
		$a_pengguna1_nama = $this->input->post('a_pengguna_id1_nama','');

		$du = $_POST;

		// interviewer 1
		$apm1 = $this->apm->check($a_pengguna1_jabatan_id,$a_pengguna1_email);
		if(isset($apm1->id)){
			$a_pengguna_id1 = $apm1->id;
			$this->apm->update($apm1->id,array('nama'=>$a_pengguna1_nama));
			$du['a_pengguna_id1'] = $apm1->id;
		}else{
			$a_pengguna_id1 = $this->apm->set(array('is_notif_interview'=>0, 'utype'=>'karyawan','a_jabatan_id'=>$a_pengguna1_jabatan_id,'email'=>$a_pengguna1_email,'nama'=>$a_pengguna1_nama,'username'=>$a_pengguna1_jabatan_id.'-'.$a_pengguna1_email,'foto'=>'','password'=>md5(rand(0,999))));
			$apm1 = $this->apm->getById($a_pengguna_id1);
			$du['a_pengguna_id1'] = $apm1->id;
		}
		if($cim->status_no > 7 && $cim->tglwaktu != $du['tglwaktu']){
			unset($du['tglwaktu']);
		}
		if(is_null($cim->a_pengguna_id1_token)){
			$du['a_pengguna_id1_token'] = $this->conumtext->genRand('',12,16);
		}

		// interviewer 2
		$a_pengguna_id2_jabatan_id = (int) $this->input->post('a_pengguna_id2_jabatan_id','');
		if($a_pengguna_id2_jabatan_id>0){
			$a_pengguna2_email = $this->input->post('a_pengguna_id2_email','');
			if(strlen($a_pengguna1_email)<=4){
				$this->status = 1053;
				$this->message = 'Silakan isi email untuk interviewer 2';
				$this->__json_out($data);
				die();
			}

			$a_pengguna2_nama = $this->input->post('a_pengguna_id2_nama','');
			if(strlen($a_pengguna2_nama)<=1){
				$this->status = 1054;
				$this->message = 'Silakan isi nama untuk interviewer 2';
				$this->__json_out($data);
				die();
			}
			$apm2 = $this->apm->check($a_pengguna_id2_jabatan_id,$a_pengguna2_email);
			if(isset($apm2->id)){
				$a_pengguna_id2 = $apm2->id;
				$this->apm->update($apm2->id,array('nama'=>$a_pengguna2_nama));
				$du['a_pengguna_id2'] = $apm2->id;
			}else{
				$a_pengguna_id2 = $this->apm->set(array('is_notif_interview'=>0, 'utype'=>'karyawan','a_jabatan_id'=>$a_pengguna_id2_jabatan_id,'email'=>$a_pengguna2_email,'nama'=>$a_pengguna2_nama,'username'=>$a_pengguna_id2_jabatan_id.'-'.$a_pengguna2_email,'foto'=>'','password'=>sha1(rand(0,999))));
				$apm2 = $this->apm->getById($a_pengguna_id2);
				$du['a_pengguna_id2'] = $apm2->id;
			}
			if(is_null($cim->a_pengguna_id2_token)){
				$du['a_pengguna_id2_token'] = $this->conumtext->genRand('',12,16);
			}
		}
		$cim->status_no = (int) $cim->status_no;
		$is_reschedule = 0;
		if($cim->status_no <= 4 && $cim->tglwaktu != $du['tglwaktu']){
			$du['status_no'] = 4;
			$du['status_teks'] = INTERVIEW_GANTI_JADWAL;
			$is_reschedule = 1;
		}
		unset($du['a_pengguna1_jabatan_id']);
		unset($du['a_pengguna2_jabatan_id']);
		unset($du['a_pengguna_id1_jabatan_id']);
		unset($du['a_pengguna_id2_jabatan_id']);
		unset($du['a_pengguna_id1_nama']);
		unset($du['a_pengguna_id2_nama']);
		unset($du['a_pengguna_id1_email']);
		unset($du['a_pengguna_id2_email']);
		$du['ldate'] = 'NOW()';
		$res = $this->cim->update($id, $du);
		if($res){
			$this->status = 200;
			$this->message = 'Success';

			if($is_reschedule){
				$this->bum->update($bum->id, array('apply_statno'=>5));
				$this->_sendEmailInterviewUndanganToPelamar($ajm,$bum,$blm,$cam,$cim,$is_reschedule);
				if(isset($apm1->id)){
					// $this->_sendEmailInterviewConfirmationToUser($ajm,$apm1,$bum,$blm,$cam,$cim,$di['a_pengguna_id1_token']);
					$this->_sendEmailInterviewUndanganToKarayawan($ajm,$apm1,$bum,$blm,$cam,$cim,1,$cim->a_pengguna_id1_token,$is_reschedule);
				}

				if(isset($apm2->id)){
					// $this->_sendEmailInterviewConfirmationToUser($ajm,$apm2,$bum,$blm,$cam,$cim,$di['a_pengguna_id2_token']);
					$this->_sendEmailInterviewUndanganToKarayawan($ajm,$apm2,$bum,$blm,$cam,$cim,2,$cim->a_pengguna_id2_token,$is_reschedule);
				}
			}
		}else{
			$this->status = 902;
			$this->message = 'Tidak dapat merubah data ke database';
		}
		$this->__json_out($data);
	}
	public function hapus_hard($id){
		$id = (int) $id;
		$d = $this->__init();
		$data = array();
		if($id<=0){
			$this->status = 500;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}
		if(!$this->admin_login && empty($id)){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$kategori = $this->cim->getById($id);
		if(!isset($kategori->id)){
			$this->status = 520;
			$this->message = 'ID not found or has been deleted';
			$this->__json_out($data);
			die();
		}
		$res = $this->cim->del($id);
		if($res){
			$this->status = 200;
			$this->message = 'Berhasil';
		}else{
			$this->status = 902;
			$this->message = 'Tidak dapat menghapus cabang';
		}
		$this->__json_out($data);
	}

	public function hapus($c_interview_id){
		$c_interview_id = (int) $c_interview_id;
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$this->status = 200;
		$this->message = 'Berhasil';
		$cim = $this->cim->getById($c_interview_id);
		if(!isset($cim->id)){
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$res = $this->cim->update($cim->id,array('is_active'=>0,'a_pengguna_id1_token'=>'null','a_pengguna_id2_token'=>'null','status_no'=>-9,'status_teks'=>INTERVIEW_DIHAPUS));
		if($res){
			$this->status = 200;
			$this->message = 'Berhasil';

			$cam = $this->cam->getById($cim->c_apply_id);
			$cim_count = $this->cim->countByApplyId($cim->c_apply_id);
			if(empty($cim_count) && isset($cam->b_user_id)){
				$this->bum->update($cam->b_user_id, array('apply_statno'=>3));
				$camdu = array('interview_hasil'=>'null', 'interview_notes'=>'null');
				if(empty($cam->is_process) || !empty($cam->is_failed)){
					$camdu['is_process'] = 1;
					$camdu['is_failed'] = 0;
				}
				$this->cam->update($cam->id, $camdu);
			}
		}else{
			$this->status = 922;
			$this->message = 'Gagal menghapus jadwal interview dari database';
		}

		$this->__json_out(array());
	}

	public function batalkan($c_apply_id,$c_interview_id){
		$c_apply_id = (int) $c_apply_id;
		$c_interview_id = (int) $c_interview_id;
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$this->status = 200;
		$this->message = 'Berhasil';
		$cim = $this->cim->getById($c_interview_id);
		if(!isset($cim->id)){
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}

		if($c_apply_id != $cim->c_apply_id){
			$data = new stdClass();
			$this->status = 903;
			$this->message = 'This ID not belong to this';
			$this->__json_out($data);
			die();
		}

		$cim->status_no = (int) $cim->status_no;
		if($cim->status_no == 8 || $cim->status_no == 9){
			$data = new stdClass();
			$this->status = 904;
			$this->message = 'Tidak bisa dibatalkan, Interview sudah selesai';
			$this->__json_out($data);
			die();
		}
		if($cim->status_no == -8 || $cim->status_no == -9){
			$data = new stdClass();
			$this->status = 906;
			$this->message = 'Interview sudah dibatalkan';
			$this->__json_out($data);
			die();
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->cim->update($cim->id,array('a_pengguna_id1_token'=>'null','a_pengguna_id2_token'=>'null','status_no'=>-8,'status_teks'=>INTERVIEW_DIBATALKAN));

		$this->__json_out(array());
	}

}
