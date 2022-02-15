<?php
/**
* API_Front/user
*/
class Verification extends JI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_usermodule_model", "buum");
  }

  public function index()
  {
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
      $this->status = 200;
      $this->message = 'User verification has been confirmed';
      $this->__json_out($data);
      die();
    }

    $api_reg_token = $this->input->request('api_reg_token');
    if(strlen($api_reg_token) != 6){
      $this->status = 572;
      $this->message = 'Invalid verification code';
      $this->__json_out($data);
      die();
    }

    if($bum->api_reg_token != $api_reg_token){
      $this->status = 573;
      $this->message = 'Invalid verification code';
      $this->__json_out($data);
      die();
    }

    $du = array();
    $du['is_confirmed'] = 1;
    $du['api_reg_token'] = "null";
    $du['api_reg_date'] = "null";
    $rs = $this->bum->update($b_user_id, $du);
    if($rs){
      $this->status = 200;
      $this->message = 'User verification completed';
      $dt['sess']->user->api_reg_date = '';
      $dt['sess']->user->api_reg_token = '';
      $dt['sess']->user->is_confirmed = 1;
      $this->setKey($dt['sess']);
    }else{
      $this->status = 911;
      $this->message = 'Cannot update user verification to database';
    }
    $this->__json_out($data);
  }
}
