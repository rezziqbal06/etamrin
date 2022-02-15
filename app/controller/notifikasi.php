<?php

class Notifikasi extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("front/c_apply_model", "clm");
    }
    public function index()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $b_user_id = $data['sess']->user->id;

        $this->setTitle('Notifikasi '.$this->config->semevar->site_suffix);

        $this->putThemeContent('notifikasi/home_modal',$data);
        $this->putThemeContent('notifikasi/home',$data);
        $this->putJsContent('notifikasi/home_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }
}
