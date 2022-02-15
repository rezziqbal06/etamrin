<?php

class Tentang extends JI_Controller
{
    public $current_page = 'about';
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = $this->__init();
        $this->setTitle('Tentang '.$this->config->semevar->company_name.' '.$this->config->semevar->site_suffix);
        $this->setDescription('Pelajari selengkapnya tentang '.$this->config->semevar->site_name.'');

        $this->putThemeContent('tentang/home',$data);
        $this->loadLayout('col-1',$data);
        $this->render();
    }
}
