<?php
//account password
class Password extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('user');
        $this->load('front/b_user_model', 'bum');
    }

    public function index()
    {
        header("HTTP/1.0 404 Not Found");
        echo 'Not Found';
    }
    public function reset($kode="")
    {
        $data = $this->__init();
        if (strlen($kode)>17) {
            $user = $this->bum->getByApiWeb($kode);
            if (isset($user->id)) {
                $this->setTitle('Reset Password '.$this->config->semevar->site_suffix);
                $password = $this->input->post('password');
                if (strlen($password)>=8) {
                    if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)) {
                        $repassword = $this->input->post('repassword');
                        if ($password == $repassword) {
                            require_once(SEMEROOT.'app/controller/api_front/register.php');
                            $r = new Register;
                            $password = $r->__passClear($password);
                            $du = array();
                            $du['password'] = $r->__passGen($password);
                            $du['api_web_token'] = "null";
                            $du['api_web_date'] = "null";
                            $du['api_reg_token'] = "null";
                            $du['api_reg_date'] = "null";
                            $du['is_confirmed'] = 1;
                            $res = $this->bum->update($user->id, $du);

                            echo '<h4>Password Changed</h4>';
                            echo '<p>Please login into your apps now, by clicking <a href="'.base_url('login').'">this link</a></p>';
                            //redir(base_url('login/'));
                            die();
                        } else {
                            $data['notif'] = 'Password with Password Confirmation does not match';
                        }
                    } else {
                        $data['notif'] = 'Password must contains letter character and number';
                    }
                } else {
                    //$data['notif'] = 'Password too short!';
                }

                $data['user'] = $user;
                $data['page_sub'] = 'address';

                $this->setTitle("Reset Password ". $this->config->semevar->site_suffix);
                $this->putThemeContent('account/password', $data);
                $this->putJsReady('account/password_bottom', $data);

                $this->loadLayout('col-1', $data);
                $this->render();
            } else {
                header("HTTP/1.0 404 Not Found");
                echo '<h1>505</h1><p>Invalid Link</p>';
                die();
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo '<h1>505</h1><p>Invalid Link</p>';
            die();
        }
    }
}
