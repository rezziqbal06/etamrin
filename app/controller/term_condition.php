<?php
class Term_Condition extends JI_Controller
{
  public $current_page = 'tnc';
  public function __construct()
  {
    parent::__construct();
  }
  public function index()
  {
    $data = $this->__init();
    $this->setTitle('Term and Condition '. $this->config->semevar->site_suffix);
    $this->putThemeContent('term_condition/home',$data);
    $this->loadLayout('col-1',$data);
    $this->render();
  }
}
