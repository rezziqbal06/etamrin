<?php
/**
* API_Front/user
*/
class Reverification extends JI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_usermodule_model", "buum");
  }

  public function index()
  {
    $dt = $this->__init();
    $data = array();
    $this->__json_out($data);
  }

  public function email(){
    $dt = $this->__init();

    //default result
    $data = array();
    $data['user'] = new stdClass();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if(!$c){
      $this->status = 401;
      $this->message = 'Missing or invalid apikey';
      $this->__json_out($data);
      die();
    }
    $b_user_id = $dt['sess']->user->id;

    $bum = $this->bum->getById($b_user_id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
    if($bum->is_confirmed){
      $this->status = 701;
      $this->message = 'User has been confirmed';
      $this->__json_out($data);
      die();
    }
    if($bum->api_reg_date == date('Y-m-d')){
      $this->status = 571;
      $this->message = 'You have already requested resend email verification today. Please try again tomorrow.';
      $this->__json_out($data);
      die();
    }
    if(strlen($bum->email)<=4){
      $this->status = 572;
      $this->message = 'You have invalid email, please change your email in <a href="'.base_url().'user/profile/">User Profile</a> page.';
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
      $replacer['site_name'] = $this->config->semevar->app_name;
      $replacer['fnama'] = $bum->fnama;
      $replacer['activation_code'] = $api_reg_token;
      $r->__userEmailVerification($bum->email,$bum->fnama,$replacer);

      //cast to $user object
      $res = $this->bum->update($b_user_id,$du);
      $this->status = 200;
      $this->message = 'Silakan cek inbox atau spam, kami telah mengirim ulang email verifikasi';
    }

    $this->__json_out($data);
  }
}
