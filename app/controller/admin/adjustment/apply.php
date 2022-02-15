<?php
class Apply extends JI_Controller {

	public function __construct(){
    parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
    $this->load('api_admin/c_apply_model','cam');
    $this->load('api_admin/c_apply_progress_model','capm');
    $this->load('api_admin/c_apply_sessiontes_model','castm');
    $this->load('api_admin/c_apply_tes_model','catm');
		$this->load('api_admin/c_apply_capturetes_model','cactm');
	}
  public function index(){
    $cam = $this->cam->getDuplicate();
    $this->debug($cam);

    $capm = $this->capm->getMissing();
    $this->debug($capm);
	}
  public function action(){
    $dups = array();
    $cam = $this->cam->getDuplicate();
    foreach($cam as $ca){
      if(!isset($dups[$ca->b_user_id])) $dups[$ca->b_user_id] = array();
      $dups[$ca->b_user_id][] = $ca;
    }
    foreach($dups as $dup){
      $i=0;
      foreach($dup as $du){
        $i++;
        if($i<=1) continue;
        $this->cam->del($du->c_apply_id);
      }
    }
    $this->debug($dups);

    $this->capm->delMissing();
    $this->castm->delMissing();
    $this->catm->delMissing();

    $cactm = $this->cactm->getMissing();
    foreach($cactm as $cact){
      $f = SEMEROOT.$cact->src;
      if(is_file($f) && file_exists($f)){
        unlink($f);
      }
      $this->cactm->del($cact->id);
    }
  }

}
