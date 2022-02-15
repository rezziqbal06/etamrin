<?php
class Home extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->current_page = 'homepage';
    }
    public function index()
    {
        $data = $this->__init();


        $this->setTitle('E-Tamrin: Aplikasi Management Tugas');

        $this->putThemeContent('home/home', $data);
        $this->putJsContent("home/home_bottom", $data);
        $this->loadLayout('col-1', $data);
        $this->render();
    }
}
