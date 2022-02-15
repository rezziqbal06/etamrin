<?php
/**
* API_Front/Kandidat
*/
class Upload extends JI_Controller {
  public $allowed_keys = '';
  public $max_file_size = 2000000;
  public $currentDataProgress = 'Upload Data';
  public $last_id = 0;

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_file_model", "bufm");
    $this->load("api_front/b_user_usermodule_model", "buum");
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

  public function __uploadFile($dt,$data,$syarat_files)
  {
    //create target directory
    $targetdir = $this->config->semevar->media_user;
    if (empty(realpath(SEMEROOT.$targetdir))) {
      if (PHP_OS == "WINNT") {
        if (!is_dir(SEMEROOT.$targetdir)) mkdir(SEMEROOT.$targetdir);
      } else {
        if (!is_dir(SEMEROOT.$targetdir)) mkdir(SEMEROOT.$targetdir, 0775);
      }
    }

    $targetdir = $targetdir.DIRECTORY_SEPARATOR.date("Y");
    if (empty(realpath(SEMEROOT.$targetdir))) {
      if (PHP_OS == "WINNT") {
        if (!is_dir(SEMEROOT.$targetdir)) mkdir(SEMEROOT.$targetdir);
      } else {
        if (!is_dir(SEMEROOT.$targetdir)) mkdir(SEMEROOT.$targetdir, 0775);
      }
    }

    $targetdir = $targetdir.DIRECTORY_SEPARATOR.date("m");
    if (empty( realpath(SEMEROOT.$targetdir))) {
      if (PHP_OS == "WINNT") {
        if (!is_dir(SEMEROOT.$targetdir)) mkdir(SEMEROOT.$targetdir);
      } else {
        if (!is_dir(SEMEROOT.$targetdir)) mkdir(SEMEROOT.$targetdir, 0775);
      }
    }

    $data['processed'] = 0;
    $data['success'] = array();
    $data['failed'] = array();
    if(is_array($_FILES) && count($_FILES)){
      foreach($_FILES as $keyname=>$v){
        if(in_array($keyname, $syarat_files)){
          $data['processed']++;

          //check extension
          $filenames = pathinfo($v['name']);
          if (isset($filenames['extension'])) {
            $fileext = strtolower($filenames['extension']);
          } else {
            $data['failed'][] = 'No extension can be found';
            continue;
          }
          unset($filenames);
          if (!in_array($fileext, array("pdf","jpg","png","jpeg","gif"))) {
            $data['failed'][] = 'Invalid extension are uploaded';
            continue;
          }

          //check filesize
          $v['size'] = (int) $v['size'];
          if ($v['size'] > $this->max_file_size) {
            $data['failed'][] = 'Ukuran file terlalu besar, pastikan kurang dari '.ceil($this->max_file_size/1024000).' MB';
            continue;
          }

          //generate filename
          $filename = $dt['sess']->user->id.'.'.$keyname;
          if (file_exists(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename.'.'.$fileext)) {
            $rand = rand(0, 999);
            $filename = $dt['sess']->user->id.'.'.$keyname.'-'.$rand;
            if (file_exists(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename.'.'.$fileext)) {
              $rand = rand(1000, 99999);
              $filename = $dt['sess']->user->id.'.'.$keyname.'-'.$rand;
            }
          }
          $filename .= '.'.$fileext;

          move_uploaded_file($v['tmp_name'], SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename);
          if (is_file(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename) && file_exists(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename)) {
            $data['success'][] = 'File has been uploaded successfuly';

            $di = array(
              'b_user_id'=> $dt['sess']->user->id,
              'utype'=> $keyname,
              'folder'=> '/',
              'filesize'=> $v['size'],
              'src'=> $targetdir.DIRECTORY_SEPARATOR.$filename,
              'cdate'=> 'NOW()',
              'is_active'=> 1
            );
            $bufm = $this->bufm->check($dt['sess']->user->id,$keyname);
            if(isset($bufm->id)){
              $this->last_id = $bufm->id;
              unset($di['folder']);
              if(file_exists(SEMEROOT.$bufm->src) && is_file(SEMEROOT.$bufm->src)) unlink(SEMEROOT.$bufm->src);
              $this->bufm->update($bufm->id, $di);
              $this->_setULog($dt['sess']->user->id, 15, $keyname);
            }else{
              $this->last_id = $this->bufm->set($di);
            }
          } else {
            $data['failed']++;
          }
        }
      }
    }
    return $data;
  }

  public function index()
  {
    $data = array();
    $dt = $this->__init();

    if(!isset($dt['sess']->user->token)){
      $this->status = 401;
      $this->message = 'Unauthorized access! Please don\'t try any more :> --drosanda';
      $this->__json_out($data);
      die();
    }

    //check apikey
    $apikey = $this->input->get('apikey');
    if($dt['sess']->user->token != $apikey){
      $this->status = 402;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 405;
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

    $data = $this->__uploadFile($dt,$data,$this->config->semevar->syarat_files);

    if($data['processed']>0 && count($data['success'])>0){
      $this->status = 200;
      $this->message = 'Successful';

      //progress bar
      $progress = $this->_checkDataProgress($dt['sess']->user, $cam->id);

      //update to status number
      $is_done = 0;
      $file_utypes = array();
      $bufm = $this->bufm->getByUserId($dt['sess']->user->id);
      if(count($bufm) && is_array($bufm)){
        foreach($bufm as $f){
          if(in_array($f->utype, $this->config->semevar->syarat_files)){
            $is_done++;
            $progress['from_val']++;
          }
        }
      }

      $progress['to_val'] = count($this->config->semevar->syarat_files);

      $capm = $this->capm->check($dt['sess']->user->id, $cam->id, $this->currentDataProgress, 'data');
      if(isset($capm->id)){
        $this->capm->update($capm->id, $progress);
      }else{
        $progress['cdate'] = 'NOW()';
        $this->capm->set($progress);
      }
    }elseif($data['processed']>0){
      $this->status = 1001;
      $this->message = 'File has been processed but not match with our criteria';
      if(isset($data['failed'][0])) $this->message = $data['failed'][0];
    }else{
      $this->status = 1002;
      $this->message = 'No file(s) uploaded that meet our criteria';
    }

    $this->__json_out($data);
  }
}
