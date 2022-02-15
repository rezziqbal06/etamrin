<?php
class Offering_Letter extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load("front/a_itemoffer_model", "aiom");
    $this->load("front/a_pengguna_model", "apm");
    $this->load("front/b_user_model", "bum");
    $this->load("front/b_lowongan_banksoal_model", "blbsm");
    $this->load("front/c_apply_progress_model", "capm");
    $this->load("front/c_apply_sessiontes_model", "castm");
    $this->load("front/c_interview_model", "cim");
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

    $this->current_menu = 'kandidat_offering_letter';

    $data['cam'] = $this->cam->getByUserId($data['sess']->user->id);
    // $interview = $this->cim->getByApplyAndUtype($data['cam']->id, 'HR');
    // $pengguna = $this->apm->getById($interview->a_pengguna_id1);
    // $data['cam']->hr_nama = $pengguna->fnama;
    $data['bum'] = $this->bum->getById($data['cam']->b_user_id);
    $data['dom'] = $this->dom->getByApplyId($data['cam']->id);
    if (!isset($data['dom']->id)) {
      redir(base_url('kandidat/hasilseleksi'));
      die();
    }

    $offering_hasil = (array) json_decode($data['dom']->offering_hasil);
    $offering = $this->aiom->getAll();
    $general = [];
    $gaji = [];
    $tunjangan = [];
    foreach ($offering as $ofr) {
      $key = str_replace(' ', '_', $ofr->nama);
      $key = str_replace('/', '_', $key);
      $key = strtolower($key);
      if (isset($offering_hasil[$key])) {
        switch ($ofr->utype) {
          case 'general':
            $general[$ofr->nama] = $offering_hasil[$key];
            break;
          case 'gaji':
            $gaji[$ofr->nama] = $offering_hasil[$key];
            break;
          case 'tunjangan':
            $tunjangan[$ofr->nama] = $offering_hasil[$key];
            break;
        }
      }
    }

    $data['dom']->gaji = $gaji;
    $data['dom']->tunjangan = $tunjangan;
    $data['dom']->general = $general;
    $data['dom']->no = $offering_hasil['no'];
    $data['dom']->cdate = $offering_hasil['cdate'];
    $data['dom']->rev = $offering_hasil['rev'];


    $this->setTitle('Offering Letter ' . $this->config->semevar->site_suffix);

    $this->putThemeContent('kandidat/offering_letter/home_modal', $data);
    $this->putThemeContent('kandidat/offering_letter/home', $data);
    $this->putJsContent('kandidat/offering_letter/home_bottom', $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }
}
