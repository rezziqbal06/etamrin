<?php
class Login extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('front');
    }
    public function index()
    {
        $data = $this->__init();
        if ($this->user_login) {
            redir(base_url('kandidat/dashboard'), 0);
            die();
        }
        $this->putJsFooter(base_url('skin/front/js/browser_detect.js'));
        $this->setTitle('Login '.$this->config->semevar->site_suffix);
        $this->setDescription('Login untuk masuk ke dashboard kandidat '.$this->config->semevar->company_name);

        $this->putThemeContent("login/home", $data);
        $this->putThemeContent("login/home_modal", $data);
        $this->putJsContent("login/home_bottom", $data);
        $this->loadLayout('login', $data);
        $this->render();
    }
}
