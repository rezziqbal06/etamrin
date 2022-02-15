<?php
class Interview extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_email");
    $this->load('admin/c_interview_model','cim');
  }
  public function index(){
    $today = $this->cim->getTodaySummary();
    
  }
}
