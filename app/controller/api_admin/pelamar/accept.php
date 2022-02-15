<?php
class Accept extends JI_Controller{

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
    if(empty($cam->is_failed) && empty($cam->is_process)){
			$this->status = 911;
			$this->message = 'Lamaran sudah diapprove';
			$this->__json_out($data);
			die();
    }
    if(!empty($cam->is_failed) && empty($cam->is_process) && !is_null($cam->edate)){
			$this->status = 910;
			$this->message = 'Lamaran sudah di reject';
			$this->__json_out($data);
			die();
    }
    $bum = $this->bum->getById($cam->b_user_id);
    if(isset($bum->apply_statno) && $bum->apply_statno != '9'){
      $this->bum->update($bum->id, array('apply_statno'=>'9'));
    }

    $du = array();
    $du['status_teks'] = 'Lolos';
    $du['status_last'] = '';
    $du['is_process'] = 0;
    $du['is_failed'] = 0;
    $du['edate'] = date('Y-m-d 00:00:00');
		$this->cam->update($cam->id,$du);

		$this->bum->update($bum->id, array('apply_statno'=>9));

		$this->status = 200;
		$this->message = 'Berhasil';

		$this->__json_out(array());
	}

}
