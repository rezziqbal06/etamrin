<?php
class HasilSeleksi extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load("front/b_user_model", "bum");
    $this->load("front/b_lowongan_banksoal_model", "blbsm");
    $this->load("front/c_apply_progress_model", "capm");
    $this->load("front/c_apply_sessiontes_model", "castm");
    $this->load("front/c_apply_model", "cam");
    $this->load("front/d_offer_model", "dom");
  }
  public function index()
  {
    $data = $this->__init();
    if (!$this->user_login) {
      redir(base_url('login'));
      return;
    }
    if (!isset($data['sess']->user->is_testawal_done)) {
      $data['sess']->user->is_testawal_done = 0;
      $this->setKey($data['sess']);
    }

    $this->_setULog($data['sess']->user->id, 28);

    $this->current_menu = 'kandidat_hasilseleksi';

    $data['cam'] = $this->cam->getByUserId($data['sess']->user->id);
    $data['bum'] = $this->bum->getById($data['cam']->b_user_id);
    $data['dom'] = $this->dom->getByApplyId($data['cam']->id);

    $this->setTitle('Hasil Seleksi ' . $this->config->semevar->site_suffix);

    $this->putThemeContent('kandidat/hasilseleksi/home_modal', $data);
    $this->putThemeContent('kandidat/hasilseleksi/home', $data);
    $this->putJsContent('kandidat/hasilseleksi/home_bottom', $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }
}
