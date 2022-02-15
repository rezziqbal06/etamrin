<?php
class Adjustment extends JI_Controller
{
  public $max_file_size = 2000000;

  public function __construct()
  {
    parent::__construct();
    $this->load('api_admin/a_banksoal_model', 'absm');
    $this->load('api_admin/b_user_model', 'bum');
    $this->load('api_admin/c_apply_model', 'cam');
    $this->load('api_admin/c_apply_capturetes_model', 'cactm');
    $this->load('api_admin/c_apply_sessiontes_model', 'castm');
    $this->load('api_admin/c_apply_tes_model', 'catm');
    $this->load('api_admin/c_apply_progress_model', 'capm');
  }
  public function index(){
    $tgl = array();
    $bum = $this->bum->getInvalidKode();
    foreach($bum as $b){
      $cdate = $b->cdate;
      if(!isset($tgl[$cdate])){
        $tgl[$cdate] = 0;
      }
      $tgl[$cdate]++;
      $kode = date('ymd',strtotime($cdate)).''.str_pad($tgl[$cdate],3,'0',STR_PAD_LEFT);
      $this->bum->update($b->id,array('kode'=>$kode));
    }
    // $this->bum->adjustUmur();
    $catm = $this->cam->get();
    foreach($catm as $c){
      $this->cactm->adjustment($c->b_user_id,$c->id);
      $this->catm->adjustment($c->b_user_id,$c->id);
      $this->castm->adjustment($c->b_user_id,$c->id);
      $this->capm->adjustment($c->b_user_id,$c->id);
    }
    $this->cam->adjustment();
  }
}
