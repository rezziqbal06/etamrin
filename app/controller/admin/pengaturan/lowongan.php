<?php
class Lowongan extends JI_Controller
{
  public $media_pengguna = 'media/pengguna/';
  public $errmsg = '';

  public function __construct()
  {
    parent::__construct();
    $this->setTheme('admin');
    $this->current_parent = 'pengaturan_lowongan';
    $this->current_page = 'pengaturan_lowongan';
    $this->load('admin/a_banksoal_model', 'absm');
    $this->load('admin/a_company_model', 'acm');
    $this->load('admin/a_jabatan_model', 'ajm');
    $this->load('admin/a_jabatan_kemampuan_model', 'ajkm');
    $this->load('admin/b_lowongan_model', 'blm');
    $this->load('admin/b_lowongan_banksoal_model', 'blbsm');
  }

  public function index()
  {
    $data = $this->__init();
    if (!$this->admin_login) {
      redir(base_url_admin('login'));
      die();
    }
    $this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

    $this->setTitle('Lowongan ' . $this->config->semevar->admin_site_suffix);
    $this->putThemeContent("pengaturan/lowongan/home_modal", $data);
    $this->putThemeContent("pengaturan/lowongan/home", $data);

    $this->putJsReady("pengaturan/lowongan/home_bottom", $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }

  public function baru()
  {
    $data = $this->__init();
    if (!$this->admin_login) {
      redir(base_url_admin('login'));
      die();
    }

    $data['posisi'] = $this->ajm->getSelect();
    $data['alamat'] = $this->acm->getSelect("offline");
    $this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

    $this->setTitle('Lowongan Baru' . $this->config->semevar->admin_site_suffix);
    $this->putThemeContent("pengaturan/lowongan/baru", $data);
    $this->putJsReady("pengaturan/lowongan/baru_bottom", $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }

  public function edit($id)
  {
    $data = $this->__init();
    if (!$this->admin_login) {
      redir(base_url_admin('login'));
      die();
    }
		$id = (int) $id;
		if($id<=0) $id=0;

    $data['lowongan'] = $this->blm->getById($id);
    if (!isset($data['lowongan']->id)) {
      redir(base_url_admin('pengaturan/lowongan'));
      die();
    }

    $data['alamat'] = $this->acm->getSelect("offline");
    $data['posisi'] = $this->ajm->getSelect();

    $data['absm_utype'] = array();
    $data['absm_utype']['Cs'] = new stdClass();
    $data['absm_utype']['Cs']->soals = array();
    $data['absm_utype']['Disc'] = new stdClass();
    $data['absm_utype']['Disc']->soals = array();
    $data['absm_utype']['Gratyo'] = new stdClass();
    $data['absm_utype']['Gratyo']->soals = array();
    $absm = $this->absm->getActive();
    foreach($absm as $a){
      $a->id = (int) $a->id;
      $utype = ucwords($a->utype);
      if(!isset($data['absm_utype'][$utype])){
        continue;
        $data['absm_utype'][$utype] = new stdClass();
        $data['absm_utype'][$utype]->soals = array();
      }
      $data['absm_utype'][$utype]->soals[$a->id] = $a;
    }

    $data['blbsm'] = $this->blbsm->getByLowonganId($id);

    // $this->debug($data['blbsm']);
    // die();

    $this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

    $this->setTitle('Edit Lowongan #' . $data['lowongan']->id . ' ' . $this->config->semevar->admin_site_suffix);

    $this->putThemeContent("pengaturan/lowongan/edit_modal", $data);
    $this->putThemeContent("pengaturan/lowongan/edit", $data);
    $this->putJsReady("pengaturan/lowongan/edit_bottom", $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }

  public function detail($id)
  {
    $data = $this->__init();
    if (!$this->admin_login) {
      redir(base_url_admin('login'));
      die();
    }
		$id = (int) $id;
		if($id<=0) $id=0;

    $data['lowongan'] = $this->blm->getById($id);
    if (!isset($data['lowongan']->id)) {
      redir(base_url_admin('pengaturan/lowongan'));
      die();
    }

    if (empty($data['lowongan']->is_freshg)) {
      $data['lowongan']->is_freshg = "";
    } else {
      $data['lowongan']->is_freshg = "Fresh Graduated Welcomed";
    }

    $awal = date_create();
    $akhir = date_create($data['lowongan']->edate);
    $diff = date_diff($awal, $akhir);
    $data['lowongan']->expired = $diff->y ? $diff->y . ' tahun ' : '';
    $data['lowongan']->expired .= $diff->m ? $diff->m . ' bulan ' : '';
    $data['lowongan']->expired .= $diff->d ? $diff->d . ' hari ' : '';
    $data['lowongan']->expired .= !$diff->d ? 'Hari ini terakhir' : ' lagi';

    $data['kemampuan'] = $this->ajkm->getByJabatanId($data['lowongan']->a_jabatan_id);

    $data['tes_sequence'] = $this->blbsm->getByLowonganId($data['lowongan']->id);

    $data['jabatan_interviewer1'] = $this->ajm->getById($data['lowongan']->a_jabatan_id_uinterview1);
    $data['jabatan_interviewer2'] = $this->ajm->getById($data['lowongan']->a_jabatan_id_uinterview2);


    $this->setTitle('Detail Lowongan ' . $data['lowongan']->id . ' ' . $this->config->semevar->admin_site_suffix);
    $this->putThemeContent("pengaturan/lowongan/detail", $data);

    // $this->putJsReady("pengaturan/lowongan/edit_bottom", $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }

  public function tes($id)
  {
    $data = $this->__init();
    if (!$this->admin_login) {
      redir(base_url_admin('login'));
      die();
    }

    $data['lowongan'] = $this->blm->getById($id);
    if (!isset($data['lowongan']->id)) {
      redir(base_url_admin('pengaturan/lowongan'));
      die();
    }

    $data['posisi'] = $this->ajm->getSelect();
    $this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

    $this->setTitle('Edit Lowongan #' . $data['lowongan']->id . ': Urutan Tes  ' . $this->config->semevar->admin_site_suffix);

    $this->putThemeContent("pengaturan/lowongan/tes_modal", $data);
    $this->putThemeContent("pengaturan/lowongan/tes", $data);
    $this->putJsReady("pengaturan/lowongan/tes_bottom", $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }
}
