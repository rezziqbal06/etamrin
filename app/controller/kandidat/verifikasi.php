<?php
/**
* Kandidat
*/
class Verifikasi extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load('front/b_user_model', 'bum');
  }
  public function index()
  {
    $data = $this->__init();
    if(!$this->user_login){
      redir(base_url());
      return;
    }
    $data['bum'] = $this->bum->getById($data['sess']->user->id);
    if($data['bum']->is_confirmed){
      redir(base_url('dashboard'),0);
      return;
    }else{
      redir(base_url('kandidat/verifikasi/email'),0);
      return;
    }
  }
  public function email()
  {
    $data = $this->__init();
    if(!$this->user_login){
      redir(base_url('login'));
      return;
    }

    $data['bum'] = $this->bum->getById($data['sess']->user->id);
    if($data['bum']->is_confirmed){
      redir(base_url('kandidat/dashboard'));
      return;
    }

    $this->setTitle('Verifikasi Email '.$this->config->semevar->site_suffix);

    $this->putThemeContent('kandidat/verifikasi/email_modal',$data);
    $this->putThemeContent('kandidat/verifikasi/email',$data);
    $this->putJsContent('kandidat/verifikasi/email_bottom',$data);
    $this->loadLayout('login',$data);
    $this->render();
  }
  public function data(){
    $data = $this->__init();
    if(!$this->user_login){
      redir(base_url('login'));
      return;
    }
    if(!isset($data['sess']->user->is_testawal_done)){
      $data['sess']->user->is_testawal_done = 0;
      $this->setKey($data['sess']);
    }
    $this->load('front/c_apply_model', 'cam');
    $this->load('front/b_lowongan_banksoal_model', 'blbsm');
    $this->load('front/b_user_keluarga_model', 'bukgm');

    $data['testskill'] = array();
    $data['cam'] = $this->cam->getByUserId($data['sess']->user->id);
    if(!isset($data['cam']->id)){
      redir(base_url('kandidat/dashboard'));
      return;
    }
    if(!empty($data['cam']->is_process)){
      redir(base_url('kandidat/dashboard'));
      return;
    }
    if(!empty($data['cam']->is_failed)){
      redir(base_url('kandidat/dashboard'));
      return;
    }
    $data['bum'] = $this->bum->getById($data['sess']->user->id);

    $this->load('front/b_user_pendidikan_model','bupm');
    $data['bupm'] = $this->bupm->getLatestByUserId($data['sess']->user->id);

    $this->setTitle('Verifikasi Data '.$this->config->semevar->site_suffix);

    $this->putThemeContent('kandidat/verifikasi/data_modal',$data);
    $this->putThemeContent('kandidat/verifikasi/data',$data);
    $this->putJsContent('kandidat/verifikasi/data_bottom',$data);
    $this->loadLayout('col-2-left',$data);
    $this->render();
  }
}
