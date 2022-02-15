<?php

class Joblist extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load('front/a_jabatan_model', 'ajm');
    $this->load('front/a_jabatan_kemampuan_model', 'ajkm');
    $this->load('front/b_lowongan_model', 'blm');
    $this->load('front/b_user_model', 'bum');
    $this->load('front/c_apply_model', 'cam');
    $this->lib("seme_curl", 'curl');

    $this->current_page = 'career';
  }
  public function index()
  {
    $data = $this->__init();
    $kabkota = $this->curl->get("https://alamat.thecloudalert.com/api/kabkota/get/");
    $wilayah = json_decode($kabkota);
    $data['is_disabled_header_logged_in'] = 1;

    $data['keyword'] = $this->input->request('keyword');

    $data['wilayah'] = $wilayah->result;

    $data['jabatan'] = $this->ajm->getAll();
    $data['pendidikan'] = $this->ajm->getPendidikan();

    $data['select_first_icon'] = '<i class="fa fa-exclamation-triangle"></i>';
    $data['select_first_text'] = 'Silakan pilih joblist terlebih dahulu untuk melakukan proses seleksi rekrutmen.';

    $this->setTitle('New Job ' . $this->config->semevar->site_suffix);

    $this->putThemeContent('joblist/home', $data);
    $this->putJsContent("joblist/home_bottom", $data);
    $this->loadLayout('col-1', $data);
    $this->render();
  }

  public function detail($id)
  {
    $data = $this->__init();

    $id = (int) $id;
    if (empty($id)) {
      redir(base_url('joblist'));
      return;
    }

    $lowongan = $this->blm->getById($id);
    if (!isset($lowongan->id)) {
      redir(base_url('joblist'));
      return;
    }
    $data['is_disabled_header_logged_in'] = 1;
    $data['is_jobslist'] = 1;

    $this->putJsFooter('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
    $this->loadCss('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min', 'before');

    $data['requirement'] = $this->ajkm->getByJabatanId($lowongan->a_jabatan_id);

    $data['lowongan'] = $lowongan;
    $data['similiar'] = $this->blm->getSimiliar($lowongan->a_jabatan_id, $lowongan->id);
    unset($lowongan);
    foreach ($data['similiar'] as &$gd) {
      $awal = date_create();
      $akhir = date_create($gd->edate);
      $diff = date_diff($awal, $akhir);
      $gd->expired = $diff->y ? $diff->y . ' tahun ' : '';
      $gd->expired .= $diff->m ? $diff->m . ' bulan ' : '';
      $gd->expired .= $diff->d ? $diff->d . ' hari ' : '';
      $gd->expired .= !$diff->d ? 'Hari ini terakhir' : ' lagi';
    }

    $data['is_can_apply'] = 1;
    $data['pesan_btn_apply'] = "";

    // if ($this->user_login) {
    //   if ($this->cam->countByUserId($data['sess']->user->id) > 0) {
    //     $data['is_can_apply'] = 0;
    //     $data['pesan_btn_apply'] = 'Maaf tidak dapat apply saat ini.\nMungkin anda sudah apply atau\nsudah menjalani proses rekrutmen kami.';
    //   }
    // }

    if ($data['lowongan']->id) {
      if (!isset($data['sess']->jobs)) $data['sess']->jobs = new stdClass();
      $data['sess']->jobs->id = (int) $data['lowongan']->id;
      $data['sess']->jobs->referrer = '';
      if(isset($_SERVER['HTTP_REFERER'])){
        if(strstr($_SERVER['HTTP_REFERER'], base_url())) $_SERVER['HTTP_REFERER']='';
        $data['sess']->jobs->referrer = $_SERVER['HTTP_REFERER'];
      }
      if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        $data['sess']->jobs->referrer = $_SERVER['HTTP_X_REQUESTED_WITH'];
      }
      $this->setKey($data['sess']);
    }

    $this->setTitle(''.$data['lowongan']->nama.' Vacancy ' . $this->config->semevar->site_suffix);
    $this->setKeyword($data['lowongan']->nama);
    $this->setDescription($this->config->semevar->company_name.' is now open recruitment for '.$data['lowongan']->nama.' '.date('Y').'. Apply now!');

    $this->putThemeContent('joblist/detail_modal', $data);
    $this->putThemeContent('joblist/detail', $data);
    $this->putJsContent("joblist/detail_bottom", $data);
    $this->loadLayout('col-1', $data);
    $this->render();
  }
  public function applied()
  {
    $data = $this->__init();
    if (!$this->user_login) {
      redir(base_url('login'));
      return;
    }

    $data['cam'] = $this->cam->getByUser($data['sess']->user->id);
    if (!isset($data['cam']->id)) {
      redir(base_url('joblist/?select_first'));
      return;
    }
    $data['is_disabled_header_logged_in'] = 1;
    $data['is_jobslist'] = 1;

    $data['bum'] = $this->bum->getById($data['sess']->user->id);

    $this->setTitle($data['cam']->status_last . ' ' . $this->config->semevar->site_suffix);

    $this->putThemeContent('joblist/applied', $data);
    $this->loadLayout('login', $data);
    $this->render();
  }
}
