<?php
/**
* API_Front/user
*/
class Keterangan extends JI_Controller {

  public $currentDataProgress = 'Data Keterangan Lainnya';

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_pertanyaan_model", "apm");
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_usermodule_model", "buum");
    $this->load("api_front/b_user_jawaban_model", "bujm");
    $this->load("api_front/c_apply_model", "cam");
    $this->load("api_front/c_apply_progress_model", "capm");
  }

  public function _checkDataProgress($bum, $c_apply_id){
    $progress = array();
    $progress['b_user_id'] = $bum->id;
    $progress['c_apply_id'] = $c_apply_id;
    $progress['utype'] = 'data';
    $progress['ldate'] = 'NOW()';
    $progress['stepkey'] = $this->currentDataProgress;
    $progress['from_val'] = 0;
    $progress['to_val'] = 0;
    $progress['is_done'] = 0;
    return $progress;
  }

  public function index()
  {
    $dt = $this->__init();
    $data = array();
    $this->__json_out($data);
  }

  public function update(){
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

    $this->status = 200;
    $this->message = 'Berhasil';

    $progress = $this->_checkDataProgress($bum, $cam->id);

    $jawabans = $this->input->post('jawabans','');
    if(empty($jawabans) || !is_array($jawabans)) $jawabans = array();

    $pertanyaan = array();
    foreach($this->apm->get() as $p){
      $pertanyaan[$p->id] = $p;
      if(is_null($p->depend_on_id)) $progress['to_val']++;
    }

    foreach($jawabans as $k=>$v){
      $di = array();
      $di['a_pertanyaan_id'] = $k;
      $di['b_user_id'] = $dt['sess']->user->id;
      $di['jawaban'] = strip_tags($v);
      $bujm = $this->bujm->getByPertanyaanIdUserId($k,$dt['sess']->user->id);
      if(isset($bujm->id)){
        $this->bujm->update($bujm->id,$di);
      }else{
        $this->bujm->set($di);
      }
      if(!empty($di['jawaban']) || strlen($di['jawaban'])) $progress['from_val']++;
    }

    $capm = $this->capm->check($dt['sess']->user->id, $cam->id, $this->currentDataProgress, 'data');
    if(isset($capm->id)){
      $this->capm->update($capm->id, $progress);
    }else{
      $progress['cdate'] = 'NOW()';
      $this->capm->set($progress);
    }

    $this->_setULog($dt['sess']->user->id, 13, 'keterangan lainnya');

    $this->__json_out($data);
  }

}
