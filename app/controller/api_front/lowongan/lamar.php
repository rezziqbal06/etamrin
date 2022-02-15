<?php
/**
* API_Front/lowongan
*/
class Lamar extends JI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load("api_front/b_lowongan_model", "blm");
    $this->load("api_front/c_apply_model", "clm");
  }
  public function index($id=''){
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 401;
      $this->message = 'Harus login, silakan login / daftar dulu';
      $this->__json_out($data);
      return;
    }
    $now = strtotime('now');
    $b_user_id = $d['sess']->user->id;

    $data['clm'] = $this->clm->check($b_user_id);
    if(isset($data['clm']->id)){
      if($data['clm']->is_process){
        $this->status = 700;
        $this->message = 'Belum bisa melamar karena ada lamaran yang masih diproses';
        $this->__json_out($data);
        return;
      }else{
        if($now > strtotime($data['clm']->edate)){
          $this->status = 701;
          $this->message = 'Lamaran anda sebelumnya telah gagal, anda bisa melamar setelah '.$this->__dateIndonesia($data['clm']->edate,'tanggal');
          $this->__json_out($data);
          return;
        }
      }
    }

    $id = (int) $id;
    if ($id<=0) {
      $this->status = 506;
      $this->message = 'ID Tidak valid';
      $this->__json_out($data);
      die();
    }

    $data['blm'] = $this->blm->getById($id);
    if(!isset($data['blm']->id)){
      $this->status = 570;
      $this->message = 'ID data lowongan tidak ditemukan';
      $this->__json_out($data);
      return;
    }

    if(strtotime($data['blm']->edate > $now)){
      $this->status = 701;
      $this->message = 'Lowongan ini telah ditutup';
      $this->__json_out($data);
      return;
    }
    $di = array(
      'b_user_id' => $b_user_id,
      'b_lowongan_id' => $id,
      'status_last' => 'menunggu seleksi',
      'cdate' => 'NOW()',
      'edate' => 'NULL',
      'is_failed' => '0',
      'is_failed' => '0',
      'is_process' => '1',
      'is_active' => '1'
    );
    $res = $this->clm->set($di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
    }else{
      $this->status = 970;
      $this->message = 'Lamaran gagal diproses';
    }

    $this->__json_out($data);
  }

  public function default_set($b_user_alamat_id)
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
      $this->status = 506;
      $this->message = 'ID Alamat Pelanggan tidak valid';
      $this->__json_out($data);
      die();
    }

    $b_user_id = $d['sess']->user->id;

    $this->status = 902;
    $this->message = 'ID Alamat tidak valid';
    $alamat = $this->buam->getByIdAndUserId($b_user_alamat_id, $b_user_id);
    if (isset($alamat->id)) {
      $this->status = 200;
      $this->message = 'Berhasil';
      $this->buam->setDefault($b_user_id, $b_user_alamat_id);
      $duu = array(
        'alamat' => $alamat->alamat,
        'kecamatan' => $alamat->kecamatan,
        'kabkota' => $alamat->kabkota,
        'provinsi' => $alamat->provinsi,
        'kodepos' => $alamat->kodepos
      );
      $this->bum->update($b_user_id, $duu);
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
