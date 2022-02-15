<?php
/**
* API_Front/kandidat
*/
class Pendidikan extends JI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_jobhistory_model", 'bupdm');
    $this->load("api_front/b_user_pendidikan_model", 'bupdm');
    $this->load("api_front/b_user_usermodule_model", "buum");
  }

  public function index()
  {
    $dt = $this->__init();
    $data = array();
    $this->__json_out($data);
  }

  public function riwayat()
  {
    $dt = $this->__init();
    $data = array();

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->bupdm->getByUserId($dt['sess']->user->id);
    $this->__json_out($data);
  }

  public function riwayat_baru(){
    $dt = $this->__init();
    $data = array();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if(!$c){
      $this->status = 401;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
    if(isset($bum->is_edit_disabled) && !empty($bum->is_edit_disabled)){
      $this->status = 944;
      $this->message = 'Maaf! Pada tahap ini Anda sudah tidak bisa hapus / edit data lagi';
      $this->__json_out($data);
      return;
    }

    $jenjang = $this->input->post('jenjang','');
    $nama = $this->input->post('nama','');
    $jurusan = $this->input->post('jurusan','');
    $lokasi = $this->input->post('lokasi','');
    $tahun_mulai = $this->input->post('tahun_mulai','');
    $tahun_selesai = $this->input->post('tahun_selesai','');
    $nilai = $this->input->post('nilai','');
    $keterangan = $this->input->post('keterangan','');

    if(empty(strlen($nama))){
      $this->status = 1101;
      $this->message = 'Nama sekolah / universitas harus diisi';
      $this->__json_out($data);
    }

    if(empty(strlen($lokasi))){
      $this->status = 1102;
      $this->message = 'Lokasi sekolah / universitas harus diisi';
      $this->__json_out($data);
    }

    if(strlen($tahun_mulai) != 4){
      $this->status = 1103;
      $this->message = 'Tahun mulai harus diisi';
      $this->__json_out($data);
    }

    if(strlen($tahun_selesai) != 4){
      $this->status = 1104;
      $this->message = 'Tahun selesai harus diisi';
      $this->__json_out($data);
    }

    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = strip_tags($v);
    }
    $di['b_user_id'] = $dt['sess']->user->id;
    $res = $this->bupdm->set($di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
    }else{
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

    $this->_setULog($dt['sess']->user->id, 13, 'riwayat pendidikan');

    $this->__json_out($data);
  }

  public function riwayat_detail($id)
  {
    $dt = $this->__init();
    $id = (int) $id;
    if($id<=0) $id=0;
    $data = new stdClass();

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->bupdm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($data->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }
    $this->__json_out($data);
  }

  public function riwayat_edit($id){
    $dt = $this->__init();
    $id = (int) $id;
    if($id<=0) $id=0;
    $data = array();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if(!$c){
      $this->status = 401;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
    if(isset($bum->is_edit_disabled) && !empty($bum->is_edit_disabled)){
      $this->status = 944;
      $this->message = 'Maaf! Pada tahap ini Anda sudah tidak bisa hapus / edit data lagi';
      $this->__json_out($data);
      return;
    }

    $bupdm = $this->bupdm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($bupdm->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }

    $jenjang = $this->input->post('jenjang','');
    $nama = $this->input->post('nama','');
    $jurusan = $this->input->post('jurusan','');
    $lokasi = $this->input->post('lokasi','');
    $tahun_mulai = $this->input->post('tahun_mulai','');
    $tahun_selesai = $this->input->post('tahun_selesai','');
    $nilai = $this->input->post('nilai','');
    $keterangan = $this->input->post('keterangan','');

    if(empty(strlen($nama))){
      $this->status = 1101;
      $this->message = 'Nama sekolah / universitas harus diisi';
      $this->__json_out($data);
    }

    if(empty(strlen($lokasi))){
      $this->status = 1102;
      $this->message = 'Lokasi sekolah / universitas harus diisi';
      $this->__json_out($data);
    }

    if(strlen($tahun_mulai) != 4){
      $this->status = 1103;
      $this->message = 'Tahun mulai harus diisi';
      $this->__json_out($data);
    }

    if(strlen($tahun_selesai) != 4){
      $this->status = 1104;
      $this->message = 'Tahun selesai harus diisi';
      $this->__json_out($data);
    }
    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = strip_tags($v);
    }
    $di['b_user_id'] = $dt['sess']->user->id;

    $res = $this->bupdm->update($id,$di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
    }else{
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

    $this->_setULog($dt['sess']->user->id, 13, 'riwayat pendidikan');

    $this->__json_out($data);
  }

  public function riwayat_hapus($id)
  {
    $dt = $this->__init();
    $id = (int) $id;
    if($id<=0) $id=0;
    $data = new stdClass();

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
      $this->__json_out($data);
      die();
    }
    if(isset($bum->is_edit_disabled) && !empty($bum->is_edit_disabled)){
      $this->status = 944;
      $this->message = 'Maaf! Pada tahap ini Anda sudah tidak bisa hapus / edit data lagi';
      $this->__json_out($data);
      return;
    }

    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->bupdm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($data->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }

    $res = $this->bupdm->del($id);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
    }else{
      $this->status = 989;
      $this->message = 'Gagal menghapus data dari database';
    }
    $this->__json_out($data);
  }

}
