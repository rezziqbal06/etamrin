<?php

/**
 * API_Front/user
 */
class Offering extends JI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/d_offer_model", "dom");
    $this->lib("seme_upload", 'se');
  }
  public function index()
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 400;
      $this->message = 'Harus login';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
  }

  public function response($d_offer_id)
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 400;
      $this->message = 'Harus login';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $d_offer_id = (int) $d_offer_id;
    if (empty($d_offer_id)) {
      $this->status = 506;
      $this->message = 'ID Offering tidak ditemukan';
      $this->__json_out($data);
      die();
    }

    $status = $this->input->post('status');
    if (empty($status)) {
      $this->status = 402;
      $this->message = 'Respon Calon Karyawan tidak ditemukan';
      $this->__json_out($data);
      die();
    }
    $b_user_id = $d['sess']->user->id;

    $this->status = 902;
    $this->message = 'ID Offering tidak valid';
    $dom = $this->dom->getById($d_offer_id);
    if (isset($dom->id)) {

      $du = array();
      $du['status'] = $status;
      $nego = $this->input->post('nego');
      if (isset($nego) && (int) $nego != 0) {
        $offering_nego['gaji_pokok'] = $nego;
        $offering_nego = json_encode($offering_nego);
        $du['offering_nego'] = $offering_nego;
      }

      $ttd = $this->input->post('ttd');
      $res_ttd = $this->se->upload_file_from_dataurl($ttd, "ttd", $dom->id);
      if ($res_ttd->status == 200) {
        $du['ttd'] = $res_ttd->file;
      }

      $res = $this->dom->update($dom->id, $du);
      if ($res) {
        $this->status = 200;
        switch ($status) {
          case 'setuju':
            $this->message = 'Silakan tunggu step selanjutnya';
            break;
          case 'tidak setuju':
            $this->message = 'Silakan tunggu step selanjutnya';
            break;
          case 'mengundurkan diri':
            $this->message = 'Terima kasih atas infonya. Sampai berjumpa di lain waktu.';
            break;
          default:
            $this->message = 'Silakan tunggu step selanjutnya';
            break;
        }
      } else {
        $this->status = 900;
        $this->message = 'Gagal';
      }
    }

    $this->__json_out($data);
  }

  public function tambah($b_user_id)
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 400;
      $this->message = 'Harus login';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $b_user_id = $d['sess']->user->id;
    $di = array();
    $di['b_user_id'] = $b_user_id;
    $di['nama_alamat'] = $this->input->post("nama_alamat");
    $di['alamat'] = $this->input->post("alamat");
    $di['kelurahan'] = $this->input->post("kelurahan");
    $di['kecamatan'] = $this->input->post("kecamatan");
    $di['kabkota'] = $this->input->post("kabkota");
    $di['provinsi'] = $this->input->post("provinsi");
    $di['kodepos'] = $this->input->post("kodepos");
    foreach ($di as &$d) {
      if (empty($d)) {
        $d = '';
      }
    }

    $this->status = 506;
    $this->message = 'ID Alamat Pelanggan tidak valid';
    if (strlen($di['nama_alamat']) > 0) {
      $this->status = 200;
      $this->message = 'Berhasil';
      $di = $_POST;
      $res = $this->buam->set($di);
      if (empty($res)) {
        $this->status = 900;
        $this->message = 'Tambah alamat pelanggan gagal';
      } else {
        $this->buam->setDefault($b_user_id, $res);
        $duu = array(
          'alamat' => $di['alamat'],
          'kecamatan' => $di['kecamatan'],
          'kabkota' => $di['kabkota'],
          'provinsi' => $di['provinsi'],
          'kodepos' => $di['kodepos']
        );
        $this->bum->update($b_user_id, $duu);
      }
    }

    $this->__json_out($data);
  }

  public function edit($b_user_id, $b_user_alamat_id)
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 400;
      $this->message = 'Harus login';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $b_user_alamat_id = (int) $b_user_alamat_id;
    if (empty($b_user_alamat_id)) {
      $this->status = 505;
      $this->message = 'ID Alamat tidak valid';
      $this->__json_out($data);
      die();
    }

    $b_user_id = $d['sess']->user->id;

    $alamat = $this->buam->getByIdAndUserId($b_user_alamat_id, $b_user_id);
    if (!isset($alamat->id)) {
      $this->status = 506;
      $this->message = 'ID alamat tidak ditemukan';
      $this->__json_out($data);
      die();
    }
    $du = array();
    $du['b_user_id'] = $b_user_id;
    $du['nama_alamat'] = $this->input->post("nama_alamat");
    $du['alamat'] = $this->input->post("alamat");
    $du['kelurahan'] = $this->input->post("kelurahan");
    $du['kecamatan'] = $this->input->post("kecamatan");
    $du['kabkota'] = $this->input->post("kabkota");
    $du['provinsi'] = $this->input->post("provinsi");
    $du['kodepos'] = $this->input->post("kodepos");
    foreach ($du as &$d) {
      if (empty($d)) {
        $d = '';
      }
    }

    $this->status = 506;
    $this->message = 'ID Alamat Pelanggan tidak valid';
    if (strlen($du['nama_alamat']) > 0) {
      $this->status = 200;
      $this->message = 'Berhasil';

      $res = $this->buam->updateByUserId($b_user_id, $b_user_alamat_id, $du);
      if (empty($res)) {
        $this->status = 900;
        $this->message = 'Edit alamat pelanggan gagal';
      } else {
        $this->buam->setDefault($b_user_id, $b_user_alamat_id);
        $duu = array(
          'alamat' => $du['alamat'],
          'kecamatan' => $du['kecamatan'],
          'kabkota' => $du['kabkota'],
          'provinsi' => $du['provinsi'],
          'kodepos' => $du['kodepos']
        );
        $this->bum->update($b_user_id, $duu);
      }
    }
    $this->__json_out($data);
  }

  public function hapus($b_user_id, $b_user_alamat_id)
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 400;
      $this->message = 'Harus login';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }

    $b_user_alamat_id = (int) $b_user_alamat_id;
    if (empty($b_user_alamat_id)) {
      $this->status = 505;
      $this->message = 'ID Alamat tidak valid';
      $this->__json_out($data);
      die();
    }

    $b_user_id = $d['sess']->user->id;
    $alamat = $this->buam->getByIdAndUserId($b_user_alamat_id, $b_user_id);
    if (!isset($alamat->id)) {
      $this->status = 506;
      $this->message = 'ID alamat tidak ditemukan';
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';
    $res = $this->buam->deleteByIdAndUserId($b_user_alamat_id, $b_user_id);
    if (empty($res)) {
      $this->status = 900;
      $this->message = 'Hapus alamat pelanggan gagal';
    }
    $this->__json_out($data);
  }

  public function detail($b_user_id, $b_user_alamat_id)
  {
    $d = $this->__init();
    $data = new stdClass();
    if (!$this->user_login) {
      $this->status = 400;
      $this->message = 'Harus login';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $b_user_id = (int) $b_user_id;
    if (empty($b_user_id)) {
      $this->status = 505;
      $this->message = 'ID Pelanggan tidak valid';
      $this->__json_out($data);
      die();
    }
    $b_user_alamat_id = (int) $b_user_alamat_id;
    if (empty($b_user_alamat_id)) {
      $this->status = 505;
      $this->message = 'ID Alamat tidak valid';
      $this->__json_out($data);
      die();
    }
    $alamat = $this->buam->getByIdAndUserId($b_user_alamat_id, $b_user_id);
    if (!isset($alamat->id)) {
      $this->status = 506;
      $this->message = 'ID alamat tidak ditemukan';
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';
    $data = $alamat;
    $this->__json_out($data);
  }
}
