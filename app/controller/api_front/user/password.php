<?php
/**
* API_Front/user
*/
class Password extends JI_Controller {

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

  public function ganti(){
		//initial
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

		//check apisess
		$b_user_id = $dt['sess']->user->id;
		$user = $this->bum->getById($b_user_id);
		if(!isset($user->id)){
			$this->status = 401;
			$this->message = 'Missing or invalid user';
			$this->__json_out($data);
			die();
		}

    require_once(SEMEROOT.'app/controller/api_front/register.php');
    $r = new Register();

		$oldpassword = $r->__passClear($this->input->post('oldpassword'));
		if(strlen($oldpassword)<=5){
			$this->status = 1722;
			$this->message = 'Missing or invalid old password';
			$this->__json_out($data);
			die();
		}

		//check with old password
		$matched = 0;
		if(md5($oldpassword) == $user->password) $matched++;
		if(password_verify($oldpassword,$user->password)) $matched++;
		if(empty($matched)){
			$this->status = 1733;
			$this->message = 'Password lama tidak sesuai, silakan coba kembali';
			$this->__json_out($data);
			die();
		}

		//building array update
		$du = array();

		//collect input
		$du['password'] = $r->__passClear($this->input->post('newpassword'));
		if(strlen($du['password'])<=5){
			$this->status = 1723;
			$this->message = 'Missing or invalid new password';
			$this->__json_out($data);
			die();
		}
		$du['password'] = $r->__passGen($du['password']);
		$res = $this->bum->update($user->id, $du);
		if($res){
			$this->status = 200;
			$this->message = 'Success';
			$user = $this->bum->getById($user->id);
		}else{
			$this->status = 1726;
			$this->message = 'Change password failed';
		}

		$data['user'] = $user;
		if(isset($data['user']->image)) $data['user']->image = $this->cdn_url($data['user']->image);
		$this->__json_out($data);
	}
}
