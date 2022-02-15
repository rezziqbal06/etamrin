<?php
class Lowongan extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load("api_admin/a_jabatan_model", 'ajm');
    $this->load("api_admin/b_lowongan_model", 'blm');
    $this->load("api_admin/b_lowongan_jabatan_model", 'bljm');
    $this->load("api_admin/b_lowongan_banksoal_model", 'blbsm');
    $this->load("api_admin/b_lowongan_banksoal_model", 'blbsm');
    $this->lib("seme_upload", 'su');
  }

  public function index()
  {
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $draw = $this->input->post("draw");
    $sval = $this->input->post("search");
    $sSearch = $this->input->request("sSearch");
    $sEcho = $this->input->post("sEcho");
    $page = $this->input->post("iDisplayStart");
    $pagesize = $this->input->request("iDisplayLength");

    $iSortCol_0 = $this->input->post("iSortCol_0");
    $sSortDir_0 = $this->input->post("sSortDir_0");

    $sSortDir_0 = $this->input->post("sSortDir_0");

    $in_utype = array();
    if (!empty($utype)) {
      $in_utype = explode(',', $utype);
    }

    $sortCol = "id";
    $sortDir = strtoupper($sSortDir_0);
    if (empty($sortDir)) {
      $sortDir = "DESC";
    }
    if (strtolower($sortDir) != "desc") {
      $sortDir = "ASC";
    }

    switch ($iSortCol_0) {
      case 0:
      $sortCol = "id";
      break;
      case 1:
      $sortCol = "nama";
      break;
      case 2:
      $sortCol = "sdate";
      break;
      case 3:
      $sortCol = "edate";
      break;
      case 4:
      $sortCol = "min_exp";
      break;
      case 5:
      $sortCol = "min_pendidikan";
      break;
      case 6:
      $sortCol = "max_usia";
      break;
      case 7:
      $sortCol = "CONCAT(is_active,'-',is_favorite)";
      break;
      case 8:
      $sortCol = "min_iq";
      break;
      case 9:
      $sortCol = "min_cs";
      break;
      default:
      $sortCol = "id";
    }

    if (empty($draw)) {
      $draw = 0;
    }
    if (empty($pagesize)) {
      $pagesize = 10;
    }
    if (empty($page)) {
      $page = 0;
    }

    $keyword = $sSearch;
    $min_edate = $this->input->post("min_edate");
    $max_edate = $this->input->post("max_edate");
    if (empty($sdate)) $sdate = "";
    if (empty($edate)) $edate = "";
    $is_deleted = 0;

    $is_active = $this->input->post('is_active');
    if (strlen($is_active)==0) $is_active = "";


    $this->status = 200;
    $this->message = 'Berhasil';
    $dcount = $this->blm->countAll($keyword, $min_edate, $max_edate, $is_active, $is_deleted);
    $ddata = $this->blm->getAll($page, $pagesize, $sortCol, $sortDir, $keyword, $min_edate, $max_edate, $is_active, $is_deleted);

    $ids = array();
    $testskill = array();
    foreach ($ddata as $gd) {
      $ids[] = $gd->id;
    }
    if(count($ids)){
      $blbsm = $this->blbsm->getByLowonganIds($ids);
      foreach($blbsm as $blbs){
        if(!isset($testskill[$blbs->b_lowongan_id])){
          $testskill[$blbs->b_lowongan_id] = array();
        }
        $testskill[$blbs->b_lowongan_id][] = $blbs;
      }
      unset($blbs,$blbsm,$ids);
    }

    foreach ($ddata as &$gd) {
      if(isset($gd->nama)){
        $gd->nama = $gd->nama;
      }
      if (isset($gd->is_active)) {
        if ($gd->is_active == 1) {
          $gd->is_active = '<span class="label label-success">Aktif</span>';
        } else {
          $gd->is_active = '<span class="label label-default">Tidak Aktif</span>';
        }
        if ($gd->is_favorite) {
          $gd->is_active .= ' <span class="label label-info">Favorite</span>';
        }
        if (isset($gd->is_freshg)) {
          if (!empty($gd->is_freshg)) {
            $gd->is_freshg = ' <span class="label label-info">Iya</span>';
          } else {
            $gd->is_freshg = ' <span class="label label-default">Tidak</span>';
          }
        }

        if (isset($gd->min_exp)) {
          $gd->min_exp .= " tahun";
        }

        if (isset($gd->max_usia)) {
          $gd->max_usia .= " tahun";
        }
      }
      if (isset($gd->sdate)) $gd->sdate = $this->__dateIndonesia($gd->sdate);
      if (isset($gd->edate)) $gd->edate = $this->__dateIndonesia($gd->edate);
      if (isset($gd->ttype)) {
        if ($gd->ttype == 'penuh') {
          $gd->ttype = 'Full Time';
        } else {
          $gd->ttype = 'Part Time';
        }
      }
      if(isset($gd->testskill) && isset($testskill[$gd->id])){
        $gd->testskill = '<ul>';
        foreach($testskill[$gd->id] as $ts){
          $gd->testskill .= '<li>'.$ts->nama.' <br><em>Passing Grade '.$ts->passing_grade.'</em>'.'</li>';
        }
        $gd->testskill .= '</ul>';
      }
    }

    $this->__jsonDataTable($ddata, $dcount);
  }
  public function baru()
  {
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    //Mengambil input post
    $di = $_POST;
    foreach($_POST as $k=>$v){
      if($k=='deskripsi') continue;
      $di[$k] = strip_tags($v);
    }
    if (!isset($di['a_company_id'])) $di['a_company_id'] = '';
    $di['a_company_id'] = (int) $di['a_company_id'];
    if($di['a_company_id']<=0) $di['a_company_id'] = 'NULL';

    //validasi
    if(!isset($di['sgaji'])) $di['sgaji'] = 0;
    if(!isset($di['egaji'])) $di['egaji'] = 0;
    $di['sgaji'] = (float) $di['sgaji'];
    $di['egaji'] = (float) $di['egaji'];
    if($di['sgaji']<=0) $di['sgaji'] = 'NULL';
    if($di['egaji']<=0) $di['egaji'] = 'NULL';

    if (!isset($di['nama'])) {
      $di['nama'] = "";
    }
    $di['nama'] = $di['nama'];

    if(!isset($di['a_jabatan_id_uinterview1'])) $di['a_jabatan_id_uinterview1'] = '';
    $di['a_jabatan_id_uinterview1'] = (int) $di['a_jabatan_id_uinterview1'];
    if($di['a_jabatan_id_uinterview1']<=0){
      $di['a_jabatan_id_uinterview1'] = 'NULL';
    }
    if(!isset($di['a_jabatan_id_uinterview2'])) $di['a_jabatan_id_uinterview2'] = '';
    $di['a_jabatan_id_uinterview2'] = (int) $di['a_jabatan_id_uinterview2'];
    if($di['a_jabatan_id_uinterview2']<=0){
      $di['a_jabatan_id_uinterview2'] = 'NULL';
    }
    if (strlen($di['nama']) > 1) {
      $res = $this->blm->set($di);
      if ($res) {
        $ri = $this->su->upload_file("gambar", $res, "1", "image", "lowongan");
        if ($ri->status == 200) {
          $this->blm->update($res, array("gambar" => $ri->file));
        }
        $this->status = 200;
        $this->message = 'Data baru berhasil ditambahkan';
      } else {
        $this->status = 900;
        $this->message = 'Tidak dapat menyimpan data baru, silakan coba beberapa saat lagi';
      }
    } else {
      $this->status = 400;
      $this->message = "Some parameter not available";
    }
    $this->__json_out($data);
  }
  public function detail($id)
  {
    $id = (int) $id;
    $d = $this->__init();
    $data = new stdClass();
    if (!$this->admin_login && empty($id)) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';
    $data = $this->blm->getById($id);
    if (!isset($data->id)) {
      $this->status = 1004;
      $this->message = 'Data Company tidak ditemukan';
    }
    $this->__json_out($data);
  }
  public function edit()
  {
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $du = array();
    foreach($_POST as $k=>$v){
      if($k=='deskripsi') continue;
      $du[$k] = strip_tags($v);
    }
    $du['deskripsi'] = $this->input->post('deskripsi');
    if (!isset($du['id'])) {
      $du['id'] = 0;
    }
    $id = (int) $du['id'];
    unset($du['id']);

    if (!isset($du['nama'])) {
      $du['nama'] = "";
    }
    $du['nama'] = $du['nama'];
    if (!isset($du['a_company_id'])) $du['a_company_id'] = '';
    $du['a_company_id'] = (int) $du['a_company_id'];
    if($du['a_company_id']<=0) $du['a_company_id'] = 'NULL';

    //validasi
    if(!isset($du['sgaji'])) $du['sgaji'] = 0;
    if(!isset($du['egaji'])) $du['egaji'] = 0;
    $du['sgaji'] = (float) $du['sgaji'];
    $du['egaji'] = (float) $du['egaji'];
    if($du['sgaji']<=0) $du['sgaji'] = 'NULL';
    if($du['egaji']<=0) $du['egaji'] = 'NULL';
    if(!isset($du['a_jabatan_id_uinterview1'])) $du['a_jabatan_id_uinterview1'] = '';
    $du['a_jabatan_id_uinterview1'] = (int) $du['a_jabatan_id_uinterview1'];
    if($du['a_jabatan_id_uinterview1']<=0){
      $du['a_jabatan_id_uinterview1'] = 'NULL';
    }
    if(!isset($du['a_jabatan_id_uinterview2'])) $du['a_jabatan_id_uinterview2'] = '';
    $du['a_jabatan_id_uinterview2'] = (int) $du['a_jabatan_id_uinterview2'];
    if($du['a_jabatan_id_uinterview2']<=0){
      $du['a_jabatan_id_uinterview2'] = 'NULL';
    }

    if ($id > 0 && strlen($du['nama']) > 1) {
      $blm = $this->blm->getById($id);
      if (!isset($blm->id)) {
        $this->status = 401;
        $this->message = "Lowongan tidak ditemukan";
        $this->__json_out($data);
        die();
      }

      $res = $this->blm->update($id, $du);
      if ($res) {

        $ri = $this->su->upload_file("gambar", $id, "1", "image", "lowongan");
        if ($ri->status == 200) {
          $this->blm->update($id, array("gambar" => $ri->file));
        }

        $this->status = 200;
        $this->message = 'Perubahan berhasil diterapkan';
      } else {
        $this->status = 901;
        $this->message = 'Tidak dapat melakukan perubahan ke basis data';
      }
    } else {
      $this->status = 444;
      $this->message = 'One or more parameter required';
    }
    $this->__json_out($data);
  }

  public function hapus($id)
  {
    $id = (int) $id;
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login && empty($id)) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $blm = $this->blm->getById($id);
    if (!isset($blm->id)) {
      $this->status = 401;
      $this->message = "Cabang tidak ditemukan";
      $this->__json_out($data);
      die();
    }
    $res = $this->blm->update($id, array('is_deleted'=>1));
    if (!$res) {
      $this->status = 902;
      $this->message = 'Data gagal dihapus';
    } else {
      $this->status = 200;
      $this->message = 'Berhasil';
    }
    $this->__json_out($data);
  }

  public function hapus_hard($id)
  {
    $id = (int) $id;
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login && empty($id)) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';
    $blm = $this->blm->getById($id);
    if (!isset($blm->id)) {
      $this->status = 401;
      $this->message = "Cabang tidak ditemukan";
      $this->__json_out($data);
      die();
    }
    $res = $this->blm->del($id);
    if (!$res) {
      $this->status = 902;
      $this->message = 'Data gagal dihapus';
    } else {
      $blm->gambar = str_replace('/\\', '/', $blm->gambar);
      $blm->gambar = str_replace('\\', '/', $blm->gambar);
      if (is_file($blm->gambar) && file_exists(SEMEROOT . $blm->gambar)) {
        unlink(SEMEROOT . $blm->gambar);
      }
    }
    $this->__json_out($data);
  }

  public function select2()
  {
    $d = $this->__init();
    $keyword = $this->input->request('q');
    //die($keyword);
    $ddata = $this->blm->select2($keyword);
    $datares = array();
    $i = 0;
    foreach ($ddata as $key => $value) {
      $datares["results"][$i++] = array("id" => $value->id, "text" => $value->kode . " - " . $value->nama);
    }
    header('Content-Type: application/json');
    echo json_encode($datares);
  }

  public function cari()
  {
    $keyword = $this->input->request("keyword");
    if (empty($keyword)) $keyword = "";
    $p = new stdClass();
    $p->id = 'NULL';
    $p->text = '-';
    $data = $this->blm->cari($keyword);
    array_unshift($data, $p);
    $this->__json_select2($data);
  }

  public function tes($b_lowongan_id)
  {
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $draw = $this->input->post("draw");
    $sval = $this->input->post("search");
    $sSearch = $this->input->request("sSearch");
    $sEcho = $this->input->post("sEcho");
    $page = $this->input->post("iDisplayStart");
    $pagesize = $this->input->request("iDisplayLength");

    $iSortCol_0 = $this->input->post("iSortCol_0");
    $sSortDir_0 = $this->input->post("sSortDir_0");

    $sSortDir_0 = $this->input->post("sSortDir_0");

    $is_active = $this->input->post('is_active');
    if (empty($is_active)) $is_active = "";

    $in_utype = array();
    if (!empty($utype)) {
      $in_utype = explode(',', $utype);
    }

    $sortCol = "id";
    $sortDir = strtoupper($sSortDir_0);
    if (empty($sortDir)) {
      $sortDir = "DESC";
    }
    if (strtolower($sortDir) != "desc") {
      $sortDir = "ASC";
    }

    $tbl_as = $this->blbsm->tbl_as;
    $tbl2_as = $this->blbsm->tbl2_as;

    switch ($iSortCol_0) {
      case 0:
      $sortCol = "$tbl_as.urutan";
      break;
      case 1:
      $sortCol = "$tbl2_as.utype";
      break;
      case 2:
      $sortCol = "$tbl2_as.nama";
      break;
      default:
      $sortCol = "$tbl_as.id";
    }

    if (empty($draw)) {
      $draw = 0;
    }
    if (empty($pagesize)) {
      $pagesize = 10;
    }
    if (empty($page)) {
      $page = 0;
    }

    $keyword = $sSearch;


    $this->status = 200;
    $this->message = 'Berhasil';
    $dcount = $this->blbsm->countAll($keyword, $b_lowongan_id);
    $ddata = $this->blbsm->getAll($page, $pagesize, $sortCol, $sortDir, $keyword, $b_lowongan_id);

    foreach ($ddata as &$gd) {
      if(isset($gd->utype)){
        $gd->utype = ucfirst($gd->utype);
      }
    }

    $this->__jsonDataTable($ddata, $dcount);
  }

  public function tes_tambah($id)
  {
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $data['lowongan'] = $this->blm->getById($id);
    if (!isset($data['lowongan']->id)) {
      $this->status = 2500;
      $this->message = 'Invalid Lowongan ID';
      $this->__json_out($data);
      die();
    }

    $di = $_POST;

    if(!isset($di['a_banksoal_id'])) $di['a_banksoal_id'] = 0;
    $di['a_banksoal_id'] = (int) $di['a_banksoal_id'];
    if($di['a_banksoal_id'] <= 0){
      $this->status = 2504;
      $this->message = 'Invalid Bank Soal ID';
      $this->__json_out($data);
      die();
    }

    $blbsm = $this->blbsm->check($id,$di['a_banksoal_id']);
    if(isset($blbsm->id)){
      $this->status = 2505;
      $this->message = 'Bank Soal telah ditambahkan sebelumnya';
      $this->__json_out($data);
      die();
    }

    if(!isset($di['urutan'])) $di['urutan'] = 0;
    if($di['urutan'] <= 0) $di['urutan'] = ($this->blbsm->countAll('', $id))+1;

    $di['b_lowongan_id'] = $id;
    if(!isset($di['passing_grade'])) $di['passing_grade'] = 0;
    if($di['passing_grade'] <= 0) $di['passing_grade'] = 'NULL';
    if(!isset($di['urutan'])) $di['urutan'] = 0;
    if($di['urutan'] <= 0) $di['urutan'] = 'NULL';

    $res = $this->blbsm->set($di);
    if ($res) {
      $this->status = 200;
      $this->message = 'Data baru berhasil ditambahkan';
    } else {
      $this->status = 2511;
      $this->message = 'Tidak dapat menyimpan data baru, silakan coba beberapa saat lagi';
    }
    $this->__json_out($data);
  }
  public function tes_detail($b_lowongan_id,$id)
  {

    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $b_lowongan_id = (int) $b_lowongan_id;
    $data['lowongan'] = $this->blm->getById($b_lowongan_id);
    if (!isset($data['lowongan']->id)) {
      $this->status = 2506;
      $this->message = 'Invalid Lowongan ID';
      $this->__json_out($data);
      die();
    }

    $id = (int) $id;
    $blbsm = $this->blbsm->getById($id);
    if (!isset($blbsm->id)) {
      $this->status = 2507;
      $this->message = 'Invalid Lowongan Bank Soal ID';
      $this->__json_out($data);
      die();
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $data = $this->blbsm->getById($id);
    $this->__json_out($data);
  }
  public function tes_edit($id)
  {
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $du = $_POST;

    if(!isset($du['a_banksoal_id'])) $du['a_banksoal_id'] = 0;
    $du['a_banksoal_id'] = (int) $du['a_banksoal_id'];
    if($du['a_banksoal_id'] <= 0){
      $this->status = 2504;
      $this->message = 'Invalid Bank Soal ID';
      $this->__json_out($data);
      die();
    }

    if(!isset($du['b_lowongan_id'])) $du['b_lowongan_id'] = 0;
    $du['b_lowongan_id'] = (int) $du['b_lowongan_id'];
    if($du['b_lowongan_id'] <= 0){
      $this->status = 2501;
      $this->message = 'Invalid Lowongan ID';
      $this->__json_out($data);
      die();
    }

    if(!isset($du['passing_grade'])) $du['passing_grade'] = 0;
    if($du['passing_grade'] <= 0) $du['passing_grade'] = 'NULL';
    if(!isset($du['urutan'])) $du['urutan'] = 0;
    if($du['urutan'] <= 0) $du['urutan'] = $this->blbsm->countAll('', $du['b_lowongan_id'])+1;
    $res = $this->blbsm->update($id, $du);
    if ($res) {
      $this->status = 200;
      $this->message = 'Perubahan berhasil diterapkan';
    } else {
      $this->status = 2512;
      $this->message = 'Tidak dapat melakukan perubahan ke basis data';
    }
    $this->__json_out($data);
  }

  public function tes_hapus($id)
  {
    $id = (int) $id;
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';
    $res = $this->blbsm->del($id);
    if (!$res) {
      $this->status = 2513;
      $this->message = 'Data gagal dihapus';
    }
    $this->__json_out($data);
  }
  public function urutan_tes($b_lowongan_id=''){
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $b_lowongan_id = (int) $b_lowongan_id;

    $blm = $this->blm->getById($b_lowongan_id);
    if(!isset($blm->id)){
      $this->status = 538;
      $this->message = 'Data dengan ID Lowongan tersebut tidak ditemukan';
      $this->__json_out($data);
      die();
    }

    $a_banksoal_id = $this->input->post('a_banksoal_id');
    if(!is_array($a_banksoal_id)){
      $this->status = 638;
      $this->message = 'Pengaturan tes dari ID bank soal tidak valid';
      $this->__json_out($data);
      die();
    }
    if(count($a_banksoal_id)<=0){
      $this->status = 738;
      $this->message = 'Tidak ada ID bank soal yang terkirim';
      $this->__json_out($data);
      die();
    }

    $is_rand_soal = $this->input->post('is_rand_soal');
    if(!is_array($is_rand_soal)) $is_rand_soal = array();

    $is_rand_jawaban = $this->input->post('is_rand_jawaban');
    if(!is_array($is_rand_jawaban)) $is_rand_jawaban = array();

    $passing_grade = $this->input->post('passing_grade');
    if(!is_array($passing_grade)) $passing_grade = array();

    $blbsm = $this->blbsm->delByLowonganId($b_lowongan_id);

    $i=0;
    foreach($a_banksoal_id as $k=>$v){
      $di = array();
      $di['a_banksoal_id'] = $v;
      $di['b_lowongan_id'] = $b_lowongan_id;
      $di['is_rand_soal'] = (isset($is_rand_soal[$k]) ? $is_rand_soal[$k] : 0 );
      $di['is_rand_jawaban'] = (isset($is_rand_jawaban[$k]) ? $is_rand_jawaban[$k] : 0 );
      $di['passing_grade'] =  (isset($passing_grade[$k]) ? $passing_grade[$k] : 0.0 );
      $di['urutan'] = $i++;
      $this->blbsm->set($di);
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $this->__json_out($data);
  }
  public function get(){
    $keyword = $this->input->request("keyword");
    if(empty($keyword)) $keyword="";
    $p = new stdClass();
    $p->id = 'NULL';
    $p->text = '-';
    $data = $this->blm->getSearch($keyword);
    array_unshift($data, $p);
    $this->status == 200;
    $this->message == 'Berhasil';
    $this->__json_out($data);
  }
}
