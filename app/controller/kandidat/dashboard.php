<?php
class Dashboard extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load("front/b_user_model", "bum");
    $this->load("front/b_lowongan_banksoal_model", "blbsm");
    $this->load("front/c_apply_progress_model", "capm");
    $this->load("front/c_apply_sessiontes_model", "castm");
    $this->load("front/c_apply_model", "cam");
    $this->current_menu = 'kandidat_dashboard';
  }
  public function index()
  {
    $data = $this->__init();
    if(!$this->user_login){
      redir(base_url('login'));
      return;
    }

    $this->_setULog($data['sess']->user->id, 4);

    if(!isset($data['sess']->user->is_testawal_done)){
      $data['sess']->user->is_testawal_done = 0;
      $this->setKey($data['sess']);
    }
    $data['testskill'] = array();
    $data['cam'] = $this->cam->getByUserId($data['sess']->user->id);
    if(isset($data['cam']->id)){
      if( !is_null($data['cam']->edate) && strtotime($data['cam']->edate) > strtotime('now') ){
        $data['sess']->user->is_testawal_done = 0;
        $this->setKey($data['sess']);
      }elseif( !is_null($data['cam']->edate) && strtotime($data['cam']->edate) < strtotime('now') ){

      }else{
        $data['testskill'] = $this->blbsm->getByLowonganId($data['cam']->b_lowongan_id);
      }
    }else{
      $data['sess']->user->is_testawal_done = 0;
      $this->setKey($data['sess']);
    }

    $data['test_awal'] = $this->_getViewTesAwal($data['testskill']);
    $data['psikotest'] = $this->_getViewPsikotest();
    $data['cards'] = $this->_getViewDataProgress();

    if(isset($data['cam']->id)){
      if(isset($data['cam']->date_exp_after_apply) || is_null($data['cam']->date_exp_after_apply) || strlen($data['cam']->date_exp_after_apply) != 10){
        if(isset($this->config->semevar->timeout_after_apply_hari) && intval($this->config->semevar->timeout_after_apply_hari) > 0){
          $data['cam']->date_exp_after_apply = date('Y-m-d', strtotime('+'.$this->config->semevar->timeout_after_apply_hari.' days'));
          $du = array('date_exp_after_apply' => $data['cam']->date_exp_after_apply);
          $this->cam->update($data['cam']->id, $du);
        }
      }

      $session_tes = $this->castm->getCurrent($data['sess']->user->id, $data['cam']->id);
      if(is_array($session_tes)){
        foreach($session_tes as $st){
          if($st->utype == 'kepribadian'){
            if(isset($data['psikotest']['Tes Kepribadian'])){
              $data['psikotest']['Tes Kepribadian']->progressbar = 50;
              if($st->is_done){
                $data['psikotest']['Tes Kepribadian']->progressbar = 100;
              }
            }
          }elseif($st->utype == 'cs'){
            if(isset($data['test_awal']['Tes CS'])){
              $data['test_awal']['Tes CS']->progressbar = 50;
              if($st->is_done){
                $data['test_awal']['Tes CS']->progressbar = 100;
                $data['sess']->user->is_testawal_done = 1;
                $this->setKey($data['sess']);
              }
            }
          }elseif($st->utype == 'iq'){
            if(isset($data['psikotest']['Tes Intelegensi'])){
              $data['psikotest']['Tes Intelegensi']->progressbar = 50;
              if($st->is_done){
                $data['psikotest']['Tes Intelegensi']->progressbar = 100;
              }
            }
          }else{
            if(isset($data['test_awal'][$st->nama])){
              $data['test_awal'][$st->nama]->progressbar = 50;
              if($st->is_done){
                $data['test_awal'][$st->nama]->progressbar = 100;
              }
            }
          }
        }
      }

      $capm = $this->capm->getCurrent($data['sess']->user->id,$data['cam']->id,$utype='data');
      foreach($capm as $b){
        if(isset($data['cards'][$b->stepkey]->progressbar)){
          if($b->to_val<=0) continue;
          $data['cards'][$b->stepkey]->progressbar = ceil(($b->from_val / $b->to_val) * 100);
        }
      }
    }

    $data['bum'] = $this->bum->getById($data['sess']->user->id);


    $this->setTitle('Dashboard Kandidat / Pelamar '.$this->config->semevar->site_suffix);

    $this->putThemeContent('kandidat/dashboard/home_modal',$data);
    $this->putThemeContent('kandidat/dashboard/home',$data);
    $this->putJsContent('kandidat/dashboard/home_bottom',$data);
    $this->loadLayout('col-2-left',$data);
    $this->render();
  }
}
