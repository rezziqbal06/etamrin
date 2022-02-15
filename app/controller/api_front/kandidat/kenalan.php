<?php
/**
* API_Front/kandidat
*/
class Kenalan extends JI_Controller {
  public $data_utype = 'Kenalan';
  public $currentDataProgress = 'Data Kenalan';

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_relasi_model", 'burm');
    $this->load("api_front/c_apply_model", 'cam');
    $this->load("api_front/c_apply_progress_model", 'capm');
  }

  private function _checkDataProgress($bum, $c_apply_id){
    $progress = array();
    $progress['b_user_id'] = $bum->id;
    $progress['c_apply_id'] = $c_apply_id;
    $progress['utype'] = 'data';
    $progress['ldate'] = 'NOW()';
    $progress['stepkey'] = $this->currentDataProgress;
    $progress['from_val'] = 0;
    $progress['to_val'] = 1;
    $progress['is_done'] = 0;
    return $progress;
  }

  private function _updateDataProgress($bum,$c_apply_id=''){
    $progress = $this->_checkDataProgress($bum, $c_apply_id);
    $counter = count($this->burm->getByUserId($bum->id, $this->data_utype));
    if($counter <= 0){
      $progress['to_val'] = 1;
      $progress['from_val'] = $progress['to_val']-1;
    }else{
      $progress['to_val'] = $counter;
      $progress['from_val'] = $progress['to_val'];
    }
    $capm = $this->capm->check($bum->id, $c_apply_id, $this->currentDataProgress, 'data');
    if(isset($capm->id)){
      $this->capm->update($capm->id, $progress);
    }else{
      $progress['cdate'] = 'NOW()';
      $this->capm->set($progress);
    }
  }

  public function index()
  {
    $dt = $this->__init();
    $data = array();
    $this->status=200;
    $this->message = 'Berhasil';
    $data=$this->burm->getByUserId($dt['sess']->user->id,$this->data_utype);
    $this->__json_out($data);
  }

  public function baru(){
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

    $cam = $this->cam->getByUserId($dt['sess']->user->id);
    if(!isset($cam->id)){
      $this->status = 1081;
      $this->message = 'Belum apply lowongan';
      $this->__json_out($data);
      die();
    }

    $nama = $this->input->post('nama','');
    $jabatan = $this->input->post('jabatan','');
    $telp = $this->input->post('telp','');
    $email = $this->input->post('email','');
    $keterangan = $this->input->post('keterangan','');

    if(empty(strlen($nama))){
      $this->status = 1101;
      $this->message = 'Nama harus diisi';
      $this->__json_out($data);
    }

    if(empty(strlen($keterangan))){
      $this->status = 1102;
      $this->message = 'Keterangan hubungan dengan anda harus diisi';
      $this->__json_out($data);
    }

    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = ucwords(strip_tags($v));
    }
    $di['b_user_id'] = $dt['sess']->user->id;
    $di['utype'] = $this->data_utype;
    $res = $this->burm->set($di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
      $this->_updateDataProgress($dt['sess']->user, $cam->id);
    }else{
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

    $this->_setULog($dt['sess']->user->id, 13, 'kenalan');

    $this->__json_out($data);
  }

  public function detail($id)
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

    $data = $this->burm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($data->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }
    $this->__json_out($data);
  }

  public function edit($id){
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

    $cam = $this->cam->getByUserId($dt['sess']->user->id);
    if(!isset($cam->id)){
      $this->status = 1081;
      $this->message = 'Belum apply lowongan';
      $this->__json_out($data);
      die();
    }

    $burm = $this->burm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($burm->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }

    $nama = $this->input->post('nama','');
    $jabatan = $this->input->post('jabatan','');
    $telp = $this->input->post('telp','');
    $email = $this->input->post('email','');
    $keterangan = $this->input->post('keterangan','');

    if(empty(strlen($nama))){
      $this->status = 1101;
      $this->message = 'Nama harus diisi';
      $this->__json_out($data);
    }

    if(empty(strlen($keterangan))){
      $this->status = 1102;
      $this->message = 'Keterangan hubungan dengan anda harus diisi';
      $this->__json_out($data);
    }

    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = ucwords(strip_tags($v));
    }
    $di['b_user_id'] = $dt['sess']->user->id;
    $di['utype'] = $this->data_utype;

    $res = $this->burm->update($id,$di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
      $this->_updateDataProgress($dt['sess']->user, $cam->id);
    }else{
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

    $this->_setULog($dt['sess']->user->id, 13, 'kenalan');

    $this->__json_out($data);
  }

  public function hapus($id)
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

    $data = $this->burm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($data->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }

    $res = $this->burm->del($id);
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
