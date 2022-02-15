<?php
class Register extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('front');
        $this->page_current = 'register';
        $this->menu_current = 'register';
    }
    public function index()
    {
        $data = $this->__init();
        if ($this->user_login) {
            redir(base_url('kandidat/dashboard'), 0);
            die();
        }

        $this->putJsFooter(base_url('skin/front/js/browser_detect.js'));
        $this->putJsFooter('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
        $this->loadCss('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min','before');

        $this->setTitle('Register '.$this->config->semevar->site_suffix);
        $this->setDescription('Daftar sekarang untuk menjadi member '.$this->config->semevar->site_name.'!');

        $this->putJsFooter($this->cdn_url('skin/front/js/jquery.nice-select.min'));
        $this->putThemeContent("register/home_modal", $data);
        $this->putThemeContent("register/home", $data);
        $this->putJsContent("register/home_bottom", $data);
        $this->loadLayout('login', $data);
        $this->render();
    }
    public function success()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $b_user_id = $data['sess']->user->id;

        $this->setTitle('Register: Success '.$this->config->semevar->site_suffix);

        $this->putThemeContent('register/success_modal',$data);
        $this->putThemeContent('register/success',$data);
        $this->putJsContent('register/success_bottom',$data);
        $this->loadLayout('login',$data);
        $this->render();
    }
}
