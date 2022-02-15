<?php
class Lupa extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('front');
    }
    public function index()
    {
        $data = $this->__init();
        $this->setTitle('Lupa password '.$this->config->semevar->site_suffix);
        $this->setDescription('Silakan reset password melalui halaman ini '.$this->config->semevar->company_name);

        //$this->loadCss(base_url('assets/css/datatables.min.css'));
        $this->putThemeContent("lupa/home", $data);
        $this->putThemeContent("lupa/home_modal", $data);
        $this->putJsContent("lupa/home_bottom", $data);
        $this->loadLayout('login', $data);
        $this->render();
    }
}
