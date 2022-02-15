<?php
/**
* API_Front/kandidat
*/
class Pekerjaan extends JI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_jobhistory_model", 'bujhm');
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
    $progress['stepkey'] = 'Riwayat Pekerjaan';
    $progress['from_val'] = 0;
    $progress['to_val'] = 0;
    $progress['is_done'] = 0;
    return $progress;
  }

  public function index()
  {
    $dt = $this->__init();

    //check apikey
    $apikey = $this->input->get('apikey');
    $c = $this->apikey_check($apikey);
    if(!$c){
      $this->status = 401;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    //default result
    $data = array();
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

    $progress = $this->_checkDataProgress($bum, $cam->id);
    $progress['to_val'] = 0;
    $progress['from_val'] = 0;

    $jabatan = $this->input->post('jabatan','');
    if(!is_array($jabatan)) $jabatan = array();
    $perusahaan_nama = $this->input->post('perusahaan_nama','');
    if(!is_array($perusahaan_nama)) $perusahaan_nama = array();
    $perusahaan_jenis = $this->input->post('perusahaan_jenis','');
    if(!is_array($perusahaan_jenis)) $perusahaan_jenis = array();
    $perusahaan_bidang = $this->input->post('perusahaan_bidang','');
    if(!is_array($perusahaan_bidang)) $perusahaan_bidang = array();
    $perusahaan_departemen = $this->input->post('perusahaan_departemen','');
    if(!is_array($perusahaan_departemen)) $perusahaan_departemen = array();
    $jabatan = $this->input->post('jabatan','');
    if(!is_array($jabatan)) $jabatan = array();
    $jabatan_akhir = $this->input->post('jabatan_akhir','');
    if(!is_array($jabatan_akhir)) $jabatan_akhir = array();
    $penempatan = $this->input->post('penempatan','');
    if(!is_array($penempatan)) $penempatan = array();
    $date_start = $this->input->post('date_start','');
    if(!is_array($date_start)) $date_start = array();
    $date_finish = $this->input->post('date_finish','');
    if(!is_array($date_finish)) $date_finish = array();
    $alasan_berhenti = $this->input->post('alasan_berhenti','');
    if(!is_array($alasan_berhenti)) $alasan_berhenti = array();
    $salary = $this->input->post('salary','');
    if(!is_array($salary)) $salary = array();
    $jobdes = $this->input->post('jobdes','');
    if(!is_array($jobdes)) $jobdes = array();
    $jenis_karyawan = $this->input->post('jenis_karyawan','');
    if(!is_array($jenis_karyawan)) $jenis_karyawan = array();

    $bujhm = $this->bujhm->getByUserId($dt['sess']->user->id);
    if(!is_array($bujhm)) $bujhm = array();

    if(count($perusahaan_nama) == 0 || count($jabatan) == 0){
      $this->status = 914;
      $this->message = 'One or more parameters are missing';
    }
    $data['yangkosong'] = array();
    foreach($jabatan as $k=>$v){
      $v = strip_tags($v);
      if (
        strlen($v) &&
        isset($perusahaan_nama[$k]) && strlen($perusahaan_nama[$k]) &&
        isset($jabatan[$k]) && strlen($jabatan[$k]) &&
        isset($penempatan[$k]) && strlen($penempatan[$k]) &&
        isset($date_start[$k]) && strlen($date_start[$k])==10
      ){
        $di = array();
        $di['b_user_id'] = $dt['sess']->user->id;
        $di['perusahaan_nama'] = isset($perusahaan_nama[$k]) ? ucwords(strip_tags($perusahaan_nama[$k])) : '';
        $di['perusahaan_jenis'] = isset($perusahaan_jenis[$k]) ? ucwords(strip_tags($perusahaan_jenis[$k])) : '';
        $di['perusahaan_bidang'] = isset($perusahaan_bidang[$k]) ? ucwords(strip_tags($perusahaan_bidang[$k])) : '';
        $di['perusahaan_departemen'] = isset($perusahaan_departemen[$k]) ? ucwords(strip_tags($perusahaan_departemen[$k])) : '';
        $di['jabatan'] = isset($jabatan[$k]) ? ucwords(strip_tags($jabatan[$k])) : '';
        $di['jabatan_akhir'] = isset($jabatan_akhir[$k]) ? ucwords(strip_tags($jabatan_akhir[$k])) : '';
        $di['penempatan'] = isset($penempatan[$k]) ? ucwords(strip_tags($penempatan[$k])) : '';
        $di['date_start'] = isset($date_start[$k]) ? strip_tags($date_start[$k]) : '';
        $di['date_finish'] = isset($date_finish[$k]) ? strip_tags($date_finish[$k]) : '';
        $di['salary'] = isset($salary[$k]) ? filter_var($salary[$k], FILTER_SANITIZE_NUMBER_INT) : '0';
        $di['alasan_berhenti'] = isset($alasan_berhenti[$k]) ? strip_tags($alasan_berhenti[$k]) : '';
        $di['jobdes'] = isset($jobdes[$k]) ? strip_tags($jobdes[$k]) : '';
        $di['jenis_karyawan'] = isset($jenis_karyawan[$k]) ? strip_tags($jenis_karyawan[$k]) : '';

        if(strlen($di['perusahaan_nama'])){
          $progress['to_val']++;
          if(strlen($di['jobdes'])){
            $progress['from_val']++;
          }
          $progress['to_val']++;
          if(strlen($di['jabatan_akhir'])){
            $progress['from_val']++;
          }
          $progress['to_val']++;
          if(strlen($di['jabatan'])){
            $progress['from_val']++;
          }
          $progress['to_val']++;
          if(strlen($di['salary'])){
            $progress['from_val']++;
          }
          $progress['to_val']++;
          if(strlen($di['salary'])){
            $progress['from_val']++;
          }
          $progress['to_val']++;
          if(strlen($di['penempatan'])){
            $progress['from_val']++;
          }
          $progress['to_val']++;
          if(strlen($di['alasan_berhenti'])){
            $progress['from_val']++;
          }
        }

        if(!isset($bujhm[$k])){
          $this->bujhm->set($di);
        }else{
          $this->bujhm->update($bujhm[$k]->id,$di);
        }
        $this->status = 200;
        $this->message = 'Berhasil';
      }
    }

    $capm = $this->capm->check($dt['sess']->user->id, $cam->id, 'Riwayat Pekerjaan', 'data');
    if(isset($capm->id)){
      $this->capm->update($capm->id, $progress);
    }else{
      $progress['cdate'] = 'NOW()';
      $this->capm->set($progress);
    }

    $this->_setULog($dt['sess']->user->id, 13, 'pekerjaan');

    $this->__json_out($data);
  }
  public function set(){
    $dt = $this->__init();
    if(!$this->user_login){
      $this->status = 401;
      $this->message = 'Akses ditolak';
      $this->__json_out(array());
      return;
    }
    $bum = $this->bum->getById($dt['sess']->user->id);

    if(!is_null($bum->is_jobhistory_exist)){
      $this->status = 200;
      $this->message = 'Flag sudah tidak null';
      $this->__json_out(array());
      return;
    }
    $cam = $this->cam->getByUserId($dt['sess']->user->id);
    if(!isset($cam->id)){
      $this->status = 1081;
      $this->message = 'Belum apply lowongan';
      $this->__json_out($data);
      die();
    }

    $is_jobhistory_exist = $this->input->post('is_jobhistory_exist', 'null');
    $this->bum->update($dt['sess']->user->id, array('is_jobhistory_exist'=>$is_jobhistory_exist));
    if(empty($is_jobhistory_exist)){
      $progress = $this->_checkDataProgress($bum, $cam->id);
      $capm = $this->capm->check($dt['sess']->user->id, $cam->id, 'Riwayat Pekerjaan', 'data');
      if(isset($capm->id)){
        $this->capm->update($capm->id, $progress);
      }else{
        $progress['cdate'] = 'NOW()';
        $this->capm->set($progress);
      }
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $this->__json_out(array());
  }

}
