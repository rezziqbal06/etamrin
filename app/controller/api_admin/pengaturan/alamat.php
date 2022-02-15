<?php
class Alamat extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load("api_admin/a_company_model", 'acm');
    $this->load("api_admin/b_alamat_interview_model", 'baim');
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

    $utype = $this->input->post('utype');

    $in_utype = array();
    if (!empty($utype)) {
      $in_utype = explode(',', $utype);
    }

    $sortCol = "date";
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
      $sortCol = "utype";
      break;
      case 2:
      $sortCol = "tempat";
      break;
      case 3:
      $sortCol = "lokasi";
      break;
      case 4:
      $sortCol = "keterangan";
      break;
      case 4:
      $sortCol = "is_active";
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


    $this->status = 200;
    $this->message = 'Berhasil';
    $dcount = $this->baim->countAll($keyword);
    $ddata = $this->baim->getAll($page, $pagesize, $sortCol, $sortDir, $keyword);

    foreach ($ddata as &$gd) {
      if (isset($gd->limit_penerimaan)) {
        $gd->limit_penerimaan = 'Max. ' . $gd->limit_penerimaan . ' hari';
      }
      if (isset($gd->is_active)) {
        if ($gd->is_active == 1) {
          $gd->is_active = '<span class="label label-success">Aktif</span>';
        } else {
          $gd->is_active = '<span class="label label-default">Tidak Aktif</span>';
        }
      }
    }
    //sleep(3);
    $another = array();
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
    if(!isset($di['utype'])) $di['utype'] = '';
    if (strlen($di['utype']) > 1) {
      $res = $this->baim->set($di);
      if ($res) {
        $this->status = 200;
        $this->message = 'Data baru berhasil ditambahkan';
      } else {
        $this->status = 900;
        $this->message = 'Tidak dapat menyimpan data baru, silakan coba beberapa saat lagi';
      }
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
    $data = $this->baim->getById($id);
    if (!isset($data->id)) {
      $this->status = 1004;
      $this->message = 'Data Alamat tidak ditemukan';
    }
    $this->__json_out($data);
  }
  public function edit($id)
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
    if (!isset($du['id'])) {
      $du['id'] = 0;
    }
    if (!isset($du['utype'])) {
      $du['utype'] = 0;
    }

    $id = (int) $id;
    if ($id > 0 && strlen($du['utype']) > 1) {
      $baim = $this->baim->getById($id);
      if (!isset($baim->id)) {
        $this->status = 401;
        $this->message = "Alamat tidak ditemukan";
        $this->__json_out($data);
        die();
      }
      $res = $this->baim->update($id, $du);
      if ($res) {
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
    if (!isset($du['id'])) {
      $du['id'] = 0;
    }
    if (!isset($du['utype'])) {
      $du['utype'] = 0;
    }

    $id = (int) $id;
    if($id<=0) $id=0;

    $baim = $this->baim->getById($id);
    if (!isset($baim->id)) {
      $this->status = 405;
      $this->message = "Data alamat tidak ditemukan";
      $this->__json_out($data);
      die();
    }

    $res = $this->baim->update($id, array('is_deleted'=>1));
    if ($res) {
      $this->status = 200;
      $this->message = 'Data berhasil dihapus';
    } else {
      $this->status = 901;
      $this->message = 'Tidak dapat melakukan perubahan ke basis data';
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
    $acm = $this->baim->getById($id);
    if (!isset($acm->id)) {
      $this->status = 401;
      $this->message = "Alamat tidak ditemukan";
      $this->__json_out($data);
      die();
    }
    $res = $this->baim->del($id);
    if (!$res) {
      $this->status = 902;
      $this->message = 'Data gagal dihapus';
    }
    $this->__json_out($data);
  }

  public function select2()
  {
    $d = $this->__init();
    $keyword = $this->input->request('q');
    //die($keyword);
    $ddata = $this->baim->select2($keyword);
    $datares = array();
    $i = 0;
    foreach ($ddata as $key => $value) {
      $datares["results"][$i++] = array("id" => $value->id, "text" => $value->kode . " - " . $value->nama);
    }
    header('Content-Type: application/json');
    echo json_encode($datares);
  }
  public function update_header_img($id)
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
    $this->status = 99;
    $this->message = 'Salah satu parameter ada yang kurang';

    $id = (int) $id;
    if ($id <= 0) {
      $this->status = 98;
      $this->message = 'Invalid ID';
      $this->__json_out($data);
      die();
    }

    $acm = $this->baim->getById($id);
    if (!isset($acm->id)) {
      $this->status = 97;
      $this->message = 'Data tidak ditemukan atau telah dihapus';
    }

    $du = array();
    $du['header_img'] = $this->__uploadHeaderImg($acm->id);
    if (strlen($du['header_img']) <= 4) {
      $this->status = 997;
      $this->message = 'Gagal upload gambar';
      $this->__json_out($data);
      die();
    }
    $res = $this->baim->update($id, $du);
    if ($res) {
      $this->status = 200;
      $this->message = 'Berhasil';
    } else {
      $this->status = 999;
      $this->message = 'Gagal merubah data header image';
    }
    $this->__json_out($data);
  }
  public function cari()
  {
    $keyword = $this->input->request("keyword");
    if (empty($keyword)) $keyword = "";
    $p = new stdClass();
    $p->id = 'NULL';
    $p->text = '-';
    $data = $this->baim->cari($keyword);
    array_unshift($data, $p);
    $this->__json_select2($data);
  }
  public function online()
  {
    $keyword = $this->input->request("keyword");
    if (empty($keyword)) $keyword = "";
    $this->__json_out($this->baim->online($keyword));
  }
  public function offline()
  {
    $keyword = $this->input->request("keyword");
    if (empty($keyword)) $keyword = "";
    $this->__json_out($this->baim->offline($keyword));
  }
}
