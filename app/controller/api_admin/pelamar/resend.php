<?php
class Resend extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->lib("conumtext");
		$this->lib("seme_email");
		$this->lib("seme_purifier");
		$this->load("api_front/a_jabatan_model",'ajm');
		$this->load("api_front/a_pengguna_model",'apm');
		$this->load("api_front/b_user_model",'bum');
		$this->load("api_front/b_lowongan_model",'blm');
		$this->load("api_front/c_apply_model",'cam');
		$this->load("api_front/c_interview_model",'cim');
	}
  public function index($c_apply_id=''){
		$d = $this->__init();
		$data = array();

    $this->__json_out($data);
  }
  public function otp($b_user_id){
		$d = $this->__init();
		$data = array();

    $bum = $this->bum->getById($b_user_id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
		if(!empty($bum->is_confirmed)){
      $this->status = 847;
      $this->message = 'Email pelamar sudah terverifikasi';
      $this->__json_out($data);
      die();
		}

    $this->status = 9999;
    $this->message = 'Send email was disabled';

    //doing send email and generates
    if ($this->email_send) {
      $du = array();
      require_once(SEMEROOT.'app/controller/api_front/register.php');
      $r = new Register();
      $api_reg_token = $r->__genRegKode($bum->id, $bum->api_reg_token);
      $du['api_reg_token'] = $api_reg_token;
      $du['api_reg_date'] = date("Y-m-d");

      $replacer = $this->_emailReplacer();
      $replacer['fnama'] = $bum->fnama;
      $replacer['activation_code'] = $api_reg_token;
      $r->__userEmailVerification($bum->email,$bum->fnama,$replacer);

      //cast to $user object
      $res = $this->bum->update($b_user_id,$du);
      $this->status = 200;
      $this->message = 'Kode OTP baru sudah dikirim ke pelamar melalui email';
    }
    $this->__json_out($data);
  }
  public function link_reset_password($b_user_id){
		$d = $this->__init();
		$data = array();

    $bum = $this->bum->getById($b_user_id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }

    $this->status = 9999;
    $this->message = 'Send email was disabled';

    //doing send email and generates
    if ($this->email_send) {
      $du = array();
      require_once(SEMEROOT.'app/controller/api_front/lupa.php');
      $r = new Lupa();
      $r->_process($bum);
      $this->status = 200;
      $this->message = 'Link untuk reset password sudah dikirim ke pelamar melalui email';
    }
    $this->__json_out($data);
  }
}
