<?php

class Kode extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("api_front/b_user_model", "bum");
        $this->current_menu = 'dashboard';
    }
    public function index()
    {
        $kode = $this->bum->genKode();
        $this->debug($kode);
        die();
    }
}
