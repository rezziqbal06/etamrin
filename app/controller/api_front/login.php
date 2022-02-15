<?php
/**
* API for login
*/
class Login extends JI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->lib("seme_log");
        $this->load("api_front/a_usermodule_model", "aum");
        $this->load("api_front/b_user_model", 'bu');
        $this->load("api_front/b_user_usermodule_model", "buum");
        $this->load("api_front/c_apply_model", "cam");
    }

    public function index()
    {
        //init
        $dt = $this->__init();

        //default result format
        $data = array();
        $data['apisess'] = '';
        $data['user'] = new stdClass();
        $data['redirect_url'] = 'login';

        //default message
        $this->status = 199;
        $this->message = 'Missing API Key';

        //check api_key
        $apikey = $this->input->get('apikey');
        $ca = $this->apikey_check($apikey);
        if ($ca) {
            require_once(SEMEROOT.'app/controller/api_front/register.php');
            $r = new Register();

            $username = $this->input->request('username','');
            $password = $r->__passClear($this->input->request('password',''));
            $fcm_token = $this->input->request('fcm_token','NULL');
            $device = $this->input->request('device','NULL');
            if (strlen($device)<=2) {
                $device = 'Browser';
            }
            if ($this->is_log) {
                $this->seme_log->write("API_Front/User::login -> ".$username." - ".$device);
            }

            $res = 0;
            if (strlen($username) && strlen($password)) {
                $res = $this->bu->auth($username);
                if (isset($res->id)) {
                    //flush old fcm_token
                    if (strlen($res->fcm_token)>6) {
                        $fcm_token_old = explode(':', $res->fcm_token);
                        if (isset($fcm_token_old[0])) {
                            $fcm_token_old = $fcm_token_old[0];
                        }
                        if (is_string($fcm_token_old) && strlen($fcm_token_old)) {
                            $this->bu->flushFcmToken($fcm_token_old);
                        }
                    }

                    //check password
                    if (md5($password) == $res->password) {
                        $res->password = password_hash($password, PASSWORD_BCRYPT);
                        $this->bu->update($res->id, array("password"=>$res->password));
                    }
                    if (!password_verify($password, $res->password)) {
                        $this->status = 1707;
                        $this->message = 'Kata sandi yang Anda masukkan salah.';
                        $this->__json_out($data);
                        die();
                    }
                    if (empty($res->is_active)) {
                        $this->status = 1708;
                        $this->message = 'Akun sudah di non-aktifkan';
                        $this->__json_out($data);
                        die();
                    }

                    if (strlen($fcm_token)>118) {
                        $this->bu->flushFcm($fcm_token);
                        $du = array();
                        $du['fcm_token'] = $fcm_token;
                        $du['device'] = $device;
                        $this->bu->update($res->id, $du);
                        $res->fcm_token = $fcm_token;
                    }
                    $token = $r->__genTokenMobile($res->id);
                    $data['apisess'] = $token;
                    $this->status = 200;
                    $this->message = 'Berhasil';
                    $res->foto = ($res->foto);
                    $res->apisess = $token;

                    $data['redirect_url'] = 'dashboard';

                    $sess = $dt['sess'];
                    if (!is_object($sess)) {
                        $sess = new stdClass();
                    }
                    if (!isset($sess->user)) {
                        $sess->user = new stdClass();
                    }

                    if(isset($res->usia) && $res->usia<=0 &&  strlen($res->bdate) == 10){
                      $dn = new DateTime('now');
                      $bd = new DateTime($res->bdate);
                      $this->bu->update($res->id,array('umur'=>$bd->diff($db)->y));
                    }

                    $sess->user = $res;
                    $sess->user->modules = $this->buum->getUserModules($res->id);
                    $sess->user->menus = new stdClass();
                    $sess->user->menus->left = array();


                    $this->setKey($sess);

                    unset($res->api_mobile_token);
                    unset($res->api_web_token);
                    unset($res->password);

                    if(isset($this->config->semevar->is_user_log) && !empty($this->config->semevar->is_user_log)){
                      $this->load('b_user_log_model','ulog');
                      $this->ulog->set(array('cdate' => date('Y-m-d H:i:s'), 'b_user_id'=>$sess->user->id, 'a_itemlog_id'=>1));
                    }

                    // paling bawah alias topprioritys
                    $data['redirect_url'] = 'kandidat/dashboard';
                    $cam = $this->cam->getByUserId($sess->user->id);
                    if(isset($cam->id)){
                      if(empty($res->is_confirmed)){
                        $data['redirect_url'] = 'kandidat/verifikasi/email';
                      }
                    }else{
                      if(isset($dt['sess']->jobs->id)){
                        $data['redirect_url'] = 'jobs/detail/'.$dt['sess']->jobs->id;
                      }
                    }
                } else {
                    $this->status = 1709;
                    $this->message = 'Email atau password yang Anda masukkan tidak cocok';
                }
            } else {
                $this->status = 1710;
                $this->message = 'Email atau password yang Anda masukkan tidak cocok';
            }
            $data['user'] = $res;
        }

        $this->__json_out($data);
    }
}
