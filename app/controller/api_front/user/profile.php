<?php
/**
* API_Front/user
*/
class Profile extends JI_Controller {

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

  public function update(){
    $dt = $this->__init();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if(!$c){
      $this->status = 401;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    //default result
    $data = array();
    $b_user_id = $dt['sess']->user->id;

    $bum = $this->bum->getById($b_user_id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }

    $fnama = strip_tags($this->input->post("fnama"));
    $lnama = strip_tags($this->input->post("lnama"));
    $telp = $this->input->post("telp");
    $tlahir = $this->input->post("tlahir");
    $bdate = $this->input->post("bdate");
    $email = $this->input->post("email");
    $jk = $this->input->post("jk");
    $pendidikan_terakhir = $this->input->post("pendidikan_terakhir");
    if(empty($pendidikan_terakhir)) $pendidikan_terakhir = "null";

    $du = array();
    if(isset($fnama)) $du['fnama'] = $fnama;
    if(isset($lnama)) $du['lnama'] = $lnama;
    if(isset($telp)) $du['telp'] = $telp;
    if(isset($no_ktp)) $du['ktp_no'] = $no_ktp;
    if(isset($bdate)) $du['bdate'] = $bdate;
    if(isset($jk)) $du['jk'] = $jk;
    if(isset($tlahir)) $du['tlahir'] = $tlahir;
    if(isset($pendidikan_terakhir)) $du['pendidikan_terakhir'] = $pendidikan_terakhir;
    if(isset($email) && strlen($email)>4){
      $du['email'] = $email;
      if($bum->email != $email){
        $cem = $this->bum->checkEmail($email);
        if (isset($cem->id)) {
            $this->status = 1700;
            $this->message = 'Email: '.$email.' already registered, try another email';
            $this->__json_out($data);
            die();
        }

        require_once(SEMEROOT.'app/controller/api_front/register.php');
        $r = new Register();
        $api_reg_token = $r->__genRegKode($b_user_id, $bum->api_reg_token);
        $du['api_reg_token'] = $api_reg_token;
        $du['api_reg_date'] = date("Y-m-d");
        $du['is_confirmed'] = 0;
      }
    }

    $res = $this->bum->update($b_user_id,$du);
    if($res){
      $this->status = 200;
      $this->message = "Success";
      $sess = $dt['sess'];

      $user = $this->bum->getById($b_user_id);
      if (!is_object($sess)) {
          $sess = new stdClass();
      }
      if (!isset($sess->user)) {
          $sess->user = new stdClass();
      }
      $sess->user = $user;
      $sess->user->menus = new stdClass();
      $sess->user->menus->left = array();

      $this->setKey($sess);
    }else{
      $this->status = 900;
      $this->message = "Cannot update data to database";
    }
    $this->__json_out($data);
  }

}
