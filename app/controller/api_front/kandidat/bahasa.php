<?php
/**
* API_Front/kandidat
*/
class Bahasa extends JI_Controller {
  public $data_utype = 'bahasa';
  public $currentDataProgress = 'Kemampuan Bahasa Asing';
  public $cert_name = 'sertifikat_bahasa';

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_skill_model", 'busm');
    $this->load("api_front/b_user_file_model", 'bufm');
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

  private function _updateDataProgress($bum, $c_apply_id=''){
    $progress = $this->_checkDataProgress($bum, $c_apply_id);
    $counter = count($this->busm->getByUserId($bum->id, $this->data_utype));
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
    $data=$this->busm->getByUserId($dt['sess']->user->id,$this->data_utype);
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
    $nilai = $this->input->post('nilai','');
    $nilai2 = $this->input->post('nilai2','');
    $noserti = $this->input->post('noserti','');
    if(strlen($noserti)>1) $noserti = 'NULL';

    if(empty(strlen($nama)) || empty(strlen($nilai)) || empty(strlen($nilai2))){
      $this->status = 1102;
      $this->message = 'Silakan lengkapi form anda';
      $this->__json_out($data);
    }

    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = ucwords(strip_tags($v));
    }
    $di['b_user_id'] = $dt['sess']->user->id;
    $di['utype'] = $this->data_utype;
    $res = $this->busm->set($di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
      $this->_updateDataProgress($dt['sess']->user, $cam->id);

      $data = array();
      require_once(SEMEROOT.'app/controller/api_front/kandidat/upload.php');
      $upload = new Upload();
      $data = $upload->__uploadFile($dt,$data,array($this->cert_name));
      $file_last_id = (int) $upload->last_id;
      if($file_last_id > 0){
        $this->busm->update($res, array('b_user_file_id'=>$file_last_id));
      }
    }else{
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

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
    $cam = $this->cam->getByUserId($dt['sess']->user->id);
    if(!isset($cam->id)){
      $this->status = 1081;
      $this->message = 'Belum apply lowongan';
      $this->__json_out($data);
      die();
    }
    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->busm->getByIdUserId($id,$dt['sess']->user->id);
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

    $busm = $this->busm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($busm->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }

    $nama = $this->input->post('nama','');
    $nilai = $this->input->post('nilai','');
    $nilai2 = $this->input->post('nilai2','');

    if(empty(strlen($nama)) || empty(strlen($nilai)) || empty(strlen($nilai2))){
      $this->status = 1102;
      $this->message = 'Silakan lengkapi form anda';
      $this->__json_out($data);
    }

    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = ucwords(strip_tags($v));
    }
    $di['b_user_id'] = $dt['sess']->user->id;
    $di['utype'] = $this->data_utype;

    $res = $this->busm->update($id,$di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
      $this->_updateDataProgress($dt['sess']->user, $cam->id);

      $data = array();
      require_once(SEMEROOT.'app/controller/api_front/kandidat/upload.php');
      $upload = new Upload();
      $data = $upload->__uploadFile($dt,$data,array($this->cert_name));
      $file_last_id = (int) $upload->last_id;
      if($file_last_id > 0){
        $this->busm->update($id, array('b_user_file_id'=>$file_last_id));
      }
    }else{
      $this->status = 989;
      $this->message = 'Gagal menambahkan data ke database';
    }

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
    
    $cam = $this->cam->getByUserId($dt['sess']->user->id);
    if(!isset($cam->id)){
      $this->status = 1081;
      $this->message = 'Belum apply lowongan';
      $this->__json_out($data);
      die();
    }

    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->busm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($data->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      die();
    }

    $res = $this->busm->del($id);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';

      $this->_updateDataProgress($dt['sess']->user,$cam->id);

      if(isset($data->b_user_file_id) && (!is_null($data->b_user_file_id) || !empty($data->b_user_file_id))){
        $bufm = $this->bufm->getById($data->b_user_file_id);
        if(file_exists(SEMEROOT.$bufm->src) && is_file(SEMEROOT.$bufm->src)){
          unlink(SEMEROOT.$bufm->src);
        }
        $this->bufm->del($data->b_user_file_id);
      }
    }else{
      $this->status = 989;
      $this->message = 'Gagal menghapus data dari database';
    }
    $this->__json_out($data);
  }

}
