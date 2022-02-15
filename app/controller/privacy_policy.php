<?php
class Privacy_Policy extends JI_Controller
{
  public $current_page = 'pp';
  public function __construct()
  {
    parent::__construct();
  }
  public function index()
  {
    $data = $this->__init();
    $this->setTitle('Privacy Policy '. $this->config->semevar->site_suffix);
    $this->putThemeContent('privacy_policy/home',$data);
    $this->loadLayout('col-1',$data);
    $this->render();
  }
}
