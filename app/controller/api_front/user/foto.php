<?php
/**
* API_Front/user
*/
class Foto extends JI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_usermodule_model", "buum");
  }

  protected function __uploadImage($keyname, $id, $ke="")
  {
    $sc = new stdClass();
    $sc->status = 500;
    $sc->message = 'Error';
    $sc->image = '';
    $sc->thumb = '';
    if (isset($_FILES[$keyname]['name'])) {
      if ($_FILES[$keyname]['size']>2000000) {
        $sc->status = 301;
        $sc->message = 'Ukuran gambar terlalu besar. Silakan pilih dengan ukuran kurang dari 2 MB';
        $this->seme_log->write('API_Member/User::__uploadImage -- forceClose '.$sc->status.' '.$sc->message);
        return $sc;
      }
      $filenames = pathinfo($_FILES[$keyname]['name']);
      if (isset($filenames['extension'])) {
        $fileext = strtolower($filenames['extension']);
      } else {
        $fileext = 'jpg';
      }

      if (!in_array($fileext, array("jpg","png","jpeg","gif"))) {
        $sc->status = 303;
        $sc->message = 'Invalid file extension, please try other image file.';
        $this->seme_log->write('API_Member/User::__uploadImage -- forceClose '.$sc->status.' '.$sc->message);
        return $sc;
      }
      $filename = "$id-$ke";
      $filethumb = $filename.'-thumb';

      $targetdir = $this->config->semevar->media_user;
      $targetdircheck = realpath(SEMEROOT.$targetdir);
      if (empty($targetdircheck)) {
        if (PHP_OS == "WINNT") {
          if (!is_dir(SEMEROOT.$targetdir)) {
            mkdir(SEMEROOT.$targetdir);
          }
        } else {
          if (!is_dir(SEMEROOT.$targetdir)) {
            mkdir(SEMEROOT.$targetdir, 0775);
          }
        }
      }

      $tahun = date("Y");
      $targetdir = $targetdir.DIRECTORY_SEPARATOR.$tahun;
      $targetdircheck = realpath(SEMEROOT.$targetdir);
      if (empty($targetdircheck)) {
        if (PHP_OS == "WINNT") {
          if (!is_dir(SEMEROOT.$targetdir)) {
            mkdir(SEMEROOT.$targetdir);
          }
        } else {
          if (!is_dir(SEMEROOT.$targetdir)) {
            mkdir(SEMEROOT.$targetdir, 0775);
          }
        }
      }

      $bulan = date("m");
      $targetdir = $targetdir.DIRECTORY_SEPARATOR.$bulan;
      $targetdircheck = realpath(SEMEROOT.$targetdir);
      if (empty($targetdircheck)) {
        if (PHP_OS == "WINNT") {
          if (!is_dir(SEMEROOT.$targetdir)) {
            mkdir(SEMEROOT.$targetdir);
          }
        } else {
          if (!is_dir(SEMEROOT.$targetdir)) {
            mkdir(SEMEROOT.$targetdir, 0775);
          }
        }
      }

      $sc->status = 998;
      $sc->message = 'Invalid file extension uploaded';
      if (in_array($fileext, array("gif", "jpg", "png","jpeg"))) {
        $filecheck = SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename.'.'.$fileext;
        if (file_exists($filecheck)) {
          unlink($filecheck);
          $rand = rand(0, 999);
          $filename = "$id-$ke-".$rand;
          $filecheck = SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename.'.'.$fileext;
          if (file_exists($filecheck)) {
            unlink($filecheck);
            $rand = rand(1000, 99999);
            $filename = "$id-$ke-".$rand;
          }
        }
        $filethumb = $filename."-thumb.".$fileext;
        $filename = $filename.".".$fileext;

        move_uploaded_file($_FILES[$keyname]['tmp_name'], SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename);
        if (is_file(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename) && file_exists(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename)) {
          if (@mime_content_type(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename) == 'image/webp') {
            $sc->status = 302;
            $sc->message = 'WebP image format currently unsupported';
            $this->seme_log->write('API_Member/User::__uploadImage -- forceClose '.$sc->status.' '.$sc->message);
            return $sc;
          }

          $this->lib("wideimage/WideImage", 'wideimage', "inc");
          if (file_exists(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filethumb) && is_file(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filethumb)) {
            unlink(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filethumb);
          }
          if (file_exists(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename) && is_file(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename)) {
            WideImage::load(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filename)->reSize(370)->saveToFile(SEMEROOT.$targetdir.DIRECTORY_SEPARATOR.$filethumb);
            $sc->status = 200;
            $sc->message = 'Successful';
            $sc->thumb = str_replace("//", "/", $targetdir.'/'.$filethumb);
            $sc->image = str_replace("'\'", "/", $targetdir.'/'.$filename);
            $sc->image = str_replace("//", "/", $targetdir.'/'.$filename);
          } else {
            $sc->status = 997;
            $sc->message = 'Failed: file image not exists';
            $this->seme_log->write('API_Member/User::__uploadImage -- forceClose '.$sc->status.' '.$sc->message);
          }
        } else {
          $sc->status = 999;
          $sc->message = 'Upload file failed';
          $this->seme_log->write('API_Member/User::__uploadImage -- forceClose '.$sc->status.' '.$sc->message);
        }
      } else {
        $sc->status = 998;
        $sc->message = 'Invalid file extension uploaded';
        $this->seme_log->write('API_Member/User::__uploadImage -- forceClose '.$sc->status.' '.$sc->message);
      }
    } else {
      $sc->status = 988;
      $sc->message = 'Keyname file does not exists';
      $this->seme_log->write('API_Member/User::__uploadImage -- forceClose '.$sc->status.' '.$sc->message);
    }
    return $sc;
  }

  public function index()
  {
    $dt = $this->__init();
    $data = array();
    $this->__json_out($data);
  }

  public function ganti(){
    $dt = $this->__init();

    //default result
    $data = array();
    $data['foto'] = '';
    $b_user_id = $dt['sess']->user->id;

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if(!$c){
      $this->status = 401;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    $du = array();

    $sc = $this->__uploadImage("foto",$b_user_id,"1");
    if(!is_object($sc)) $sc = new stdClass();
    if(!isset($sc->status)) $sc->status=0;
    if(!isset($sc->message)) $sc->message='no response from upload processor';
    if($sc->status == 200){
      $du['foto'] = $sc->image;
      $res = $this->bum->update($b_user_id,$du);
      if($res){
        $this->status = 200;
        $this->message = "Berhasil";
        $sess = $dt['sess'];

        $user = $this->bum->getById($b_user_id);
        if (!is_object($sess)) {
          $sess = new stdClass();
        }
        if (!isset($sess->user)) {
          $sess->user = new stdClass();
        }
        $sess->user = $user;
        $sess->user->menus = new stdClass();
        $sess->user->menus->left = array();

        $this->setKey($sess);
        $data['foto'] = $this->cdn_url($sc->image);
      }else{
        $this->status = 900;
        $this->message = "Gagal";
      }
    }else{
      $this->status = $sc->status;
      $this->message = $sc->message;
    }

    $this->__json_out($data);
  }
}
