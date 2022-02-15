<?php

class Dashboard extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("front/b_user_model", "bum");
        $this->load("front/c_apply_model", "clm");
        $this->current_menu = 'dashboard';
    }
    public function index()
    {
        $data = $this->__init();
        if(!$this->user_login){
          redir(base_url('login'));
          return;
        }else{
          redir(base_url('kandidat/dashboard'));
          return;
        }
    }
}
