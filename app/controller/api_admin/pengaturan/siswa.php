<?php
class Siswa extends JI_Controller
{
  public $is_updated = 0; //validasi jika ada data yang diupdate ketika import excel

  public function __construct()
  {
    parent::__construct();
    $this->load("api_admin/a_kelas_model", 'akm');
    $this->load("api_admin/b_user_model", 'bum');
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

    $is_active = $this->input->post('is_active');
    if (empty($is_active)) $is_active = "";
    $is_active = 1;

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
        $sortCol = "a_kelas_id";
        break;
      case 3:
        $sortCol = "nis";
        break;
      case 4:
        $sortCol = "angkatan";
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
    $dcount = $this->bum->countAll($keyword, $is_active);
    $ddata = $this->bum->getAll($page, $pagesize, $sortCol, $sortDir, $keyword, $is_active);



    foreach ($ddata as &$gd) {


      if (isset($gd->is_active)) {
        if ($gd->is_active == 1) {
          $gd->is_active = '<span class="label label-success">Aktif</span>';
        } else {
          $gd->is_active = '<span class="label label-default">Tidak Aktif</span>';
        }
        if (isset($gd->is_visible)) {
          if (!empty($gd->is_visible)) {
            $gd->is_active .= ' <span class="label label-info">Tampil</span>';
          }
        }
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
    $di = array();
    foreach ($_POST as $k => $v) {
      $di[$k] = $v;
      if (is_string($v)) {
        $di[$k] = strip_tags($v);
      }
    }

    //validasi
    if (!isset($di['kode'])) {
      $di['kode'] = "";
    }
    if (!isset($di['nama'])) {
      $di['nama'] = "";
    }
    if (strlen($di['nama']) > 1) {
      $requirments = array();
      $requirments = $di['a_kemampuan_id'];
      unset($di['a_kemampuan_id']);
      $di['nama'] = $di['nama'];
      $res = $this->bum->set($di);
      if ($res) {
        if (is_array($requirments) && count($requirments)) {
          $i = 0;
          $dis = array();
          foreach ($requirments as $req) {
            if (strlen($req) < 4) {
              $dis[$i]['a_kemampuan_id'] = $req;
              $dis[$i]['cdate'] = 'NOW()';
              $dis[$i]['is_active'] = 1;
              $dis[$i]['a_jabatan_id'] = $res;
            } else {
              $kid = $this->akm->set(array("nama" => $req, "cdate" => "NOW()"));
              $dis[$i]['a_kemampuan_id'] = $kid;
              $dis[$i]['cdate'] = 'NOW()';
              $dis[$i]['is_active'] = 1;
              $dis[$i]['a_jabatan_id'] = $res;
            }
            $i++;
          }

          $this->ajkm->setMass($dis);
        }
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
    $data = $this->bum->getById($id);
    if (!isset($data->id)) {
      $this->status = 1004;
      $this->message = 'Data Company tidak ditemukan';
    }
    $data->kemampuan = $this->ajkm->getByJabatanId($id);
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
    $requirments = $this->input->post('a_kemampuan_id', '');
    if (!is_array($requirments)) $requirments = array();

    $du = array();
    foreach ($_POST as $k => $v) {
      if (is_string($v)) $du[$k] = strip_tags($v);
    }
    if (!isset($du['id'])) {
      $du['id'] = 0;
    }
    $id = (int) $du['id'];
    unset($du['id']);

    if (!isset($du['nama'])) {
      $du['nama'] = "";
    }

    if ($id > 0 && strlen($du['nama']) > 1) {
      $du['nama'] = $du['nama'];
      $bum = $this->bum->getById($id);
      if (!isset($bum->id)) {
        $this->status = 401;
        $this->message = "Jabatan tidak ditemukan";
        $this->__json_out($data);
        die();
      }

      unset($du['a_kemampuan_id']);
      $res = $this->bum->update($id, $du);
      if ($res) {
        $this->ajkm->delByJabatanId($id);
        if (is_array($requirments) && count($requirments)) {
          $i = 0;
          $dis = array();
          foreach ($requirments as $req) {
            if (strlen($req) < 4) {
              $dis[$i]['a_kemampuan_id'] = $req;
              $dis[$i]['cdate'] = 'NOW()';
              $dis[$i]['is_active'] = 1;
              $dis[$i]['a_jabatan_id'] = $id;
            } else {
              $kid = $this->akm->set(array("nama" => $req, "cdate" => "NOW()"));
              $dis[$i]['a_kemampuan_id'] = $kid;
              $dis[$i]['cdate'] = 'NOW()';
              $dis[$i]['is_active'] = 1;
              $dis[$i]['a_jabatan_id'] = $id;
            }
            $i++;
          }

          $this->ajkm->setMass($dis);
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
    $this->status = 200;
    $this->message = 'Berhasil';
    $bum = $this->bum->getById($id);
    if (!isset($bum->id)) {
      $this->status = 401;
      $this->message = "Cabang tidak ditemukan";
      $this->__json_out($data);
      die();
    }
    $res = $this->bum->update($id, ['is_active' => 0]);
    if (!$res) {
      $this->status = 902;
      $this->message = 'Data gagal dihapus';
    } else {
      $this->ajkm->delByJabatanId($id);
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
    $bum = $this->bum->getById($id);
    if (!isset($bum->id)) {
      $this->status = 401;
      $this->message = "Cabang tidak ditemukan";
      $this->__json_out($data);
      die();
    }
    $res = $this->bum->del($id);
    if (!$res) {
      $this->status = 902;
      $this->message = 'Data gagal dihapus';
    } else {
      $this->ajkm->delByJabatanId($id);
    }
    $this->__json_out($data);
  }

  public function select2()
  {
    $d = $this->__init();
    $keyword = $this->input->request('q');
    //die($keyword);
    $ddata = $this->bum->select2($keyword);
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
    $data = $this->bum->cari($keyword);
    array_unshift($data, $p);
    $this->__json_select2($data);
  }

  public function __checkData($admin_id, $data)
  {
    $departemen = $data['1'];
    $nama = $data['2'];
    $usia = $data['3'];
    $pendidikan = $data['4'];
    $pengalaman = $data['5'];
    $min_iq = (int) $data['6'];
    $deskripsi = '';

    if (strlen($nama) < 1) {
      die();
    }

    //ambil a_departemen_id
    $ddepartemen = $this->adm->getByNama($departemen);
    if (!isset($ddepartemen->id)) {
      $did = [
        "nama" => $departemen,
        "deskripsi" => $deskripsi,
        "cdate" => 'NOW()',
        "is_active" => 1
      ];
      $a_departemen_id = $this->adm->set($did);
    } else {
      $a_departemen_id = $ddepartemen->id;
    }

    //manipulate
    $usia = str_replace(" tahun", '', $usia);
    $usia = (int) $usia;
    $pengalaman = str_replace(" tahun", '', $pengalaman);
    $pengalaman = (int) $pengalaman;

    $di = [
      "a_pengguna_id" => $admin_id,
      "a_departemen_id" => $a_departemen_id,
      "nama" => $nama,
      "max_usia" => $usia,
      "min_pendidikan" => $pendidikan,
      "min_exp" => $pengalaman,
      "min_iq" => $min_iq,
      "is_active" => 1
    ];

    $jabatan = $this->bum->getByNama($nama);
    if (!isset($jabatan->id)) {
      $di['cdate'] = "NOW()";
      $this->bum->set($di);
    } else {
      $this->is_updated = 1;
      $this->bum->update($jabatan->id, $di);
      $this->ajkm->delByJabatanId($jabatan->id);
    }
  }

  public function __updateKemampuan($nama, $kemampuan)
  {
    if (strlen($nama) > 1 && strlen($kemampuan) > 1) {
      $jabatan = $this->bum->getByNama($nama);
      if (isset($jabatan->id)) {
        $dkemampuan = $this->akm->getByNama($kemampuan);
        if (isset($dkemampuan->id)) {
          $a_kemampuan_id = $dkemampuan->id;
        } else {
          $a_kemampuan_id = $this->akm->set(array("nama" => trim($kemampuan), "cdate" => 'NOW()'));
        }

        $this->ajkm->set(array("a_jabatan_id" => $jabatan->id, "a_kemampuan_id" => $a_kemampuan_id, "cdate" => "NOW()"));
      }
    }
  }

  public function import()
  {
    $d = $this->__init();
    $data = array();
    $this->is_updated = 0;
    if (!$this->admin_login && empty($id)) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $this->lib("seme_spreadsheet", 'ss');

    $filename = 'file_import';

    if (!isset($_FILES[$filename]['name'])) {
      $this->status = 400;
      $this->message = "File import tidak ditemukan";
      $this->__json_out($data);
      die();
    }

    $arr_file = explode('.', $_FILES[$filename]['name']);
    $ext = end($arr_file);
    if ($ext !== 'xls' && $ext !== 'xlsx') {
      $this->status = 401;
      $this->message = "File extension not valid";
      $this->__json_out($data);
      die();
    }

    $reader = $this->ss->newReader();

    $spreadsheet = $reader->load($_FILES[$filename]['tmp_name']);

    $admin_id = $d['sess']->admin->id;

    $sheetData = $spreadsheet->getActiveSheet()->toArray();
    $nama = '';
    $data = array();
    for ($i = 3; $i < count($sheetData); $i++) {
      $kemampuan = $sheetData[$i]['7'];
      if ($i == 3) {
        $nama = $sheetData[$i]['2'];
        $this->__checkData($admin_id, $sheetData[$i]); //check apakah row tersebut sudah ada atau tidak
      } else {
        if (strlen($sheetData[$i]['2']) > 1) {
          $nama = $sheetData[$i]['2'];
          $this->__checkData($admin_id, $sheetData[$i]); //check apakah row tersebut sudah ada atau tidak
        }
      }

      $this->__updateKemampuan($nama, $kemampuan);
    }

    $this->status = 200;
    $this->message = "Berhasil Import Data";
    if ($this->is_updated) {
      $this->status = 201;
      $this->message = "Mengupdate data yang ada";
    }

    $data['is_updated'] = $this->is_updated;

    $this->__json_out($data);
  }
  public function fix()
  {
    // $bum = $this->bum->get();
    // foreach($bum as $aj){
    //   // $this->bum->update($aj->id,array('nama'=>ucwords(strtolower($aj->nama))));
    // }
  }
}
