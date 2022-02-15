<?php
/**
* API_Front
*/
class Lupa extends JI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bu');
    $this->load("api_front/b_user_usermodule_model", "buum");
  }

  /**
  * Return full link reset
  */
  public function __passwordGenerateLink($user_id)
  {
      $this->lib("conumtext");
      $token = $this->conumtext->genRand($type="str", $min=18, $max=24);
      $this->bu->setToken($user_id, $token, $kind="api_web");
      return base_url('account/password/reset/'.$token);
  }
  public function _process($user){
    $replacer = $this->_emailReplacer();
    $replacer['fnama'] = $user->fnama;
    $replacer['reset_link'] = $this->__passwordGenerateLink($user->id);

    $this->seme_email->flush();
    $this->seme_email->replyto($this->config->semevar->app_name, $this->config->semevar->email_reply);
    $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
    $this->seme_email->subject('Permintaan Link untuk Reset Password');
    $this->seme_email->to($user->email, $user->fnama);
    $this->seme_email->template('user_password_lupa');
    $this->seme_email->replacer($replacer);
    $this->seme_email->send();
  }

  public function index()
  {
    //initial
    $dt = $this->__init();

    //default result
    $data = array();
    $data['apisess'] = '';
    $data['user'] = new stdClass();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if (empty($c)) {
      $this->status = 199;
      $this->message = 'Missing or invalid apikey';
      $this->__json_out($data);
      die();
    }

    //check email
    $email = $this->input->post("email");
    if (strlen($email)>6) {
      if ($this->is_log) {
        $this->seme_log->write("API_Front/User::lupa --email $email");
      }
      $user = $this->bu->getByEmail($email);
      if (isset($user->id)) {
        if ($this->email_send) {
          $this->_process($user);
        }

        $this->status = 200;
        $this->message = 'Success, please check your email if you are registered';
      } else {
        $this->status = 1718;
        $this->message = 'It looks like the email you entered incorrect, please check again';
      }
    } else {
      $this->status = 1719;
      $this->message = 'It looks like the email you entered is incorrect, please check again';
    }

    $this->__json_out($data);
  }
}
