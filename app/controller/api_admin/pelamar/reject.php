<?php
class Reject extends JI_Controller{
	public $pelamar_reject_notification_email = 0;

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
		$this->load("api_admin/c_interview_model",'cim');
		if(isset($this->config->semevar->pelamar_reject_notification_email)){
			$this->pelamar_reject_notification_email = $this->config->semevar->pelamar_reject_notification_email;
		}
	}

  public function _sendEmailRejectionToPelamar($ajm,$bum){
    $replacer = $this->_emailReplacer();
		$replacer['pelamar_nama'] = isset($bum->fnama) ? $bum->fnama : '';
		$replacer['pelamar_posisi'] = isset($ajm->nama) ? $ajm->nama : '';
		$replacer['company_nama'] = $this->config->semevar->company_name;
    $replacer['site_name'] = $this->config->semevar->app_name;
		$replacer['email_dari'] = $this->config->semevar->email_from;
    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->company_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Mohon Maaf anda Tidak Lolos untuk posisi ('.(isset($ajm->nama) ? $ajm->nama : '').')');
    $this->seme_email->to($bum->email, $bum->fnama);
    $this->seme_email->template('pelamar_reject');
    $this->seme_email->replacer($replacer);
    $this->seme_email->send();
  }

	public function index($c_apply_id=''){
		$c_apply_id = (int) $c_apply_id;
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
		$cam = $this->cam->getById($c_apply_id);
		if(!isset($cam->id)){
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}
		$ajm = new stdClass();
		$blm = new stdClass();
    $bum = $this->bum->getById($cam->b_user_id);
    if(isset($bum->apply_statno) && $bum->apply_statno != '8'){
      $this->bum->update($bum->id, array('apply_statno'=>'8'));
			$blm = $this->blm->getById($cam->b_lowongan_id);
			if(isset($blm->a_jabatan_id)){
				$ajm = $this->ajm->getById($blm->a_jabatan_id);
			}
    }else{
			$this->status = 941;
			$this->message = 'Data user tidak ada';
			$this->__json_out($data);
			return;
		}

    if(empty($cam->is_failed) && empty($cam->is_process)){
			$this->status = 911;
			$this->message = 'Pelamar sudah diapprove';
			$this->__json_out($data);
			die();
    }
    if(!empty($cam->is_failed) && empty($cam->is_process) && !is_null($cam->edate)){
			$this->status = 910;
			$this->message = 'Pelamar sudah direject';
			$this->__json_out($data);
			die();
    }


    $du = array();
    $du['status_teks'] = 'Tidak Lolos';
    $du['status_last'] = '';
    $du['is_process'] = 0;
    $du['is_failed'] = 1;
    $du['edate'] = date('Y-m-d 00:00:00', strtotime('+180 days'));
		$this->cam->update($cam->id,$du);

		$this->bum->update($bum->id, array('apply_statno'=>8));

		$this->status = 200;
		$this->message = 'Berhasil';

		if($this->pelamar_reject_notification_email){
			$this->_sendEmailRejectionToPelamar($ajm,$bum);
		}

		$this->__json_out(array());
	}

}
