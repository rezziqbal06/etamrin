<?php
//account password
class Activate extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('user');
        $this->load('front/b_user_model', 'bum');
    }

    public function index($kode="")
    {
        $data = $this->__init();
        if (strlen($kode)>8) {
            $user = $this->bum->getByApiRegTokenSHA($kode);
            if (isset($user->id)) {
                if (empty($user->is_confirmed) && strlen($user->api_reg_token) > 0) {
                    $du = array();
                    $du['is_confirmed'] = 1;
                    $res = $this->bum->edit($user->nation_code, $user->id, $du);

                    //send email
                    $this->lib('seme_email');
                    $replacer = $this->_emailReplacer();
                    $replacer['site_name'] = $this->config->semevar->app_name;
                    $replacer['fnama'] = $user->fnama;

                    //bumilding email properties
                    $this->seme_email->replyto($this->config->semevar->app_name, $this->config->semevar->site_replyto);
                    $this->seme_email->from($this->config->semevar->site_email, $this->config->semevar->app_name);
                    $this->seme_email->subject('Your account is now Active!');
                    $this->seme_email->to($user->email, $user->fnama);
                    $this->seme_email->template('account_active');
                    $this->seme_email->replacer($replacer);
                    $this->seme_email->send();

                    $data = $this->__init();
                    $data['email_debug'] = $this->seme_email->getLog();
                    $this->setTitle('Account Activation '.$this->config->semevar->site_suffix);
                    $this->putThemeContent('account/activate', $data);
                    $this->putJsReady('account/activate_bottom', $data);
                    $this->loadLayout('col-1', $data);
                    $this->render();
                } else {
                    header("HTTP/1.0 404 Not Found");
                    echo '<h1>507</h1><p>Account already activated</p>';
                    die();
                }
            } else {
                header("HTTP/1.0 404 Not Found");
                echo '<h1>506</h1><p>Invalid Activation Link</p>';
                die();
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo '<h1>505</h1><p>Invalid Activation Link</p>';
            die();
        }
    }


    public function test_email_active()
    {
        $email = $this->input->request("email");
        if (strlen($email)<=4) {
            $email = 'daeng@cenah.co.id';
        }
        $user = $this->bum->getByEmail($email);
        if (!isset($user->id)) {
            die("unregistered email");
        }
        $nama = $user->fnama;

        //generate acativation link
        require_once(SEMEROOT.'app/controller/api_front/register.php');
        $r = new Register();
        $link = $r->__activateGenerateLink($user->id);

        //load email libary
        $this->lib('seme_email');
        $replacer = $this->_emailReplacer();
        $replacer['site_name'] = $this->app_name;
        $replacer['fnama'] = $nama;

        //bumilding email properties
        $this->seme_email->replyto($this->site_name, $this->site_replyto);
        $this->seme_email->from($this->site_email, $this->site_name);
        $this->seme_email->subject('Please confirm your '.$this->site_name.' registration');
        $this->seme_email->to($email, $nama);
        $this->seme_email->template('account_register');
        $this->seme_email->replacer($replacer);
        $this->seme_email->send();
        $this->debug($this->seme_email->getLog());
    }
}
