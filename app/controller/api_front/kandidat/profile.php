<?php
/**
* API_Front/user
*/
class Profile extends JI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_usermodule_model", "buum");
    $this->load("api_front/c_apply_model", "cam");
    $this->load("api_front/c_apply_progress_model", "capm");
  }
  private function _checkData(){
    return array(
      'fnama',
      'noktp',
      'email',
      'agama',
      'tinggi_badan',
      'berat_badan',
      'gol_darah',
      'tlahir',
      'bdate',
      'hobi',
      'tentang',
      'jenis_alamat',
      'pakai_kendaraan',
      'alamat',
      'desakel',
      'kecamatan',
      'kabkota',
      'provinsi',
      'negara',
      'domisili_kodepos',
      'domisili_alamat',
      'domisili_desakel',
      'domisili_kecamatan',
      'domisili_kabkota',
      'domisili_provinsi',
      'domisili_negara',
      'domisili_kodepos'
    );
  }

  public function _checkDataProgress($bum, $c_apply_id){
    $progress = array();
    $progress['b_user_id'] = $bum->id;
    $progress['c_apply_id'] = $c_apply_id;
    $progress['utype'] = 'data';
    $progress['ldate'] = 'NOW()';
    $progress['stepkey'] = 'Data Pribadi';
    $progress['from_val'] = 0;
    $progress['to_val'] = 0;
    $progress['is_done'] = 0;

    foreach($this->_checkData() as $k=>$v){
      if(isset($bum->{$v})){
        if(!empty($bum->{$v})){
          $progress['from_val']++;
        }else{
        }
      }else{
      }
      $progress['to_val']++;
    }
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

    $cnama = strip_tags($this->input->post("cnama"));
    $noktp = strip_tags($this->input->post("noktp"));
    $fnama = strip_tags($this->input->post("fnama"));
    $lnama = strip_tags($this->input->post("lnama"));
    $alamat = strip_tags($this->input->post("alamat",''));
    $alamat2 = strip_tags($this->input->post("alamat2",''));
    $tentang = strip_tags($this->input->post("tentang",''));
    $kodepos = strip_tags($this->input->post("kodepos",''));
    $hobi = strip_tags($this->input->post("hobi",''));
    $agama = strip_tags($this->input->post("agama",''));
    $tinggi_badan = (int) strip_tags($this->input->post("tinggi_badan",''));
    if($tinggi_badan<=0) $tinggi_badan = 'NULL';
    $berat_badan = strip_tags($this->input->post("berat_badan",''));
    if($berat_badan<=0) $berat_badan = 'NULL';
    $jenis_alamat = strip_tags($this->input->post("jenis_alamat",''));
    $gol_darah = strip_tags($this->input->post("gol_darah",''));
    if(empty($gol_darah)) $gol_darah = 'NULL';
    $social_fb = strip_tags($this->input->post("social_fb",''));
    $social_ig = strip_tags($this->input->post("social_ig",''));
    $social_linkedin = strip_tags($this->input->post("social_linkedin",''));
    $pakai_kendaraan = strip_tags($this->input->post("pakai_kendaraan",''));
    $npwp = strip_tags(strtoupper($this->input->post("npwp",'')));
    if(strlen($npwp)>1 && strlen($npwp)<=4){
      $this->status = 1012;
      $this->message = 'Nomor NPWP tidak valid';
      $this->__json_out($data);
      die();
    }
    $punya_sim = strip_tags(strtoupper($this->input->post("punya_sim",'')));
    if($punya_sim != ''){
      $nosim = strip_tags($this->input->post("nosim",''));
      if(strlen($nosim)<=4){
        $this->status = 1011;
        $this->message = 'Nomor SIM tidak valid';
        $this->__json_out($data);
        die();
      }
      switch($punya_sim){
        case 'A':
          $is_sim_a = 1;
          break;
        case 'B':
          $is_sim_b = 1;
          break;
        case 'C':
          $is_sim_c = 1;
          break;
      }
    }


    $ag = explode(', ', $this->input->post("alamat_generator",''));
    if(is_array($ag) && count($ag) == 5){
      if(isset($ag[0])) $desakel = $ag[0];
      if(isset($ag[1])) $kecamatan = $ag[1];
      if(isset($ag[2])) $kabkota = $ag[2];
      if(isset($ag[3])) $provinsi = $ag[3];
      if(isset($ag[4])) $negara = $ag[4];
    }

    $domisili_alamat = strip_tags($this->input->post("domisili_alamat",''));
    $domisili_alamat2 = strip_tags($this->input->post("domisili_alamat2",''));
    $dag = explode(', ', $this->input->post("domisili_alamat_generator",''));
    if(is_array($dag) && count($dag) == 5){
      if(isset($dag[0])) $domisili_desakel = $dag[0];
      if(isset($dag[1])) $domisili_kecamatan = $dag[1];
      if(isset($dag[2])) $domisili_kabkota = $dag[2];
      if(isset($dag[3])) $domisili_provinsi = $dag[3];
      if(isset($dag[4])) $domisili_negara = $dag[4];
    }
    $domisili_kodepos = strip_tags($this->input->post("domisili_kodepos",''));

    $telp = $this->input->post("telp");
    $tlahir = $this->input->post("tlahir");
    $bdate = $this->input->post("bdate");
    $email = $this->input->post("email");
    $jk = $this->input->post("jk");
    $pendidikan_terakhir = $this->input->post("pendidikan_terakhir");
    if(empty($pendidikan_terakhir)) $pendidikan_terakhir = "null";
    $kerja_exp_y = (int) $this->input->post("kerja_exp_y");
    if(strlen($kerja_exp_y)==0) $kerja_exp_y = "null";

    $du = array();
    if(isset($fnama)) $du['fnama'] = $fnama;
    if(isset($lnama)) $du['lnama'] = $lnama;
    if(isset($cnama)) $du['cnama'] = $cnama;
    if(isset($telp)) $du['telp'] = $telp;
    if(isset($noktp)) $du['noktp'] = $noktp;
    if(isset($bdate)) $du['bdate'] = $bdate;
    if(isset($jk)) $du['jk'] = $jk;
    if(isset($tlahir)) $du['tlahir'] = $tlahir;
    if(isset($tlahir)) $du['tentang'] = $tentang;
    if(isset($hobi)) $du['hobi'] = $hobi;
    if(isset($agama)) $du['agama'] = $agama;
    if(isset($tinggi_badan)) $du['tinggi_badan'] = $tinggi_badan;
    if(isset($berat_badan)) $du['berat_badan'] = $berat_badan;
    if(isset($jenis_alamat)) $du['jenis_alamat'] = $jenis_alamat;
    if(isset($gol_darah)) $du['gol_darah'] = $gol_darah;
    if(isset($social_ig)) $du['social_ig'] = $social_ig;
    if(isset($social_fb)) $du['social_fb'] = $social_fb;
    if(isset($social_linkedin)) $du['social_linkedin'] = $social_linkedin;
    if(isset($pakai_kendaraan)) $du['pakai_kendaraan'] = $pakai_kendaraan;
    if(isset($is_sim_a)) $du['is_sim_a'] = $is_sim_a;
    if(isset($is_sim_b)) $du['is_sim_b'] = $is_sim_b;
    if(isset($is_sim_c)) $du['is_sim_c'] = $is_sim_c;
    if(isset($npwp)) $du['npwp'] = $npwp;
    if(isset($nosim)) $du['nosim'] = $nosim;
    if(isset($kerja_exp_y)) $du['kerja_exp_y'] = $kerja_exp_y;

    if(isset($alamat)) $du['alamat'] = ($alamat);
    if(isset($alamat2)) $du['alamat2'] = ($alamat2);
    if(isset($desakel)) $du['desakel'] = ($desakel);
    if(isset($kecamatan)) $du['kecamatan'] = ($kecamatan);
    if(isset($kabkota)) $du['kabkota'] = ($kabkota);
    if(isset($provinsi)) $du['provinsi'] = ($provinsi);
    if(isset($negara)) $du['negara'] = ($negara);
    if(isset($kodepos)) $du['kodepos'] = $kodepos;

    if(isset($domisili_alamat)) $du['domisili_alamat'] = ($domisili_alamat);
    if(isset($domisili_alamat2)) $du['domisili_alamat2'] = ($domisili_alamat2);
    if(isset($domisili_desakel)) $du['domisili_desakel'] = ($domisili_desakel);
    if(isset($domisili_kecamatan)) $du['domisili_kecamatan'] = ($domisili_kecamatan);
    if(isset($domisili_kabkota)) $du['domisili_kabkota'] = ($domisili_kabkota);
    if(isset($domisili_provinsi)) $du['domisili_provinsi'] = ($domisili_provinsi);
    if(isset($domisili_negara)) $du['domisili_negara'] = ($domisili_negara);
    if(isset($domisili_kodepos)) $du['domisili_kodepos'] = $domisili_kodepos;

    if(isset($pendidikan_terakhir)) $du['pendidikan_terakhir'] = $pendidikan_terakhir;
    if(isset($email) && strlen($email)>4){
      $du['email'] = $email;
      if($bum->email != $email){
        $cem = $this->bum->checkEmail($email);
        if (isset($cem->id)) {
            $this->status = 1700;
            $this->message = 'Email: '.$email.' already registered, try another email';
            $this->__json_out($data);
            die();
        }
      }
    }

    $res = $this->bum->update($dt['sess']->user->id,$du);
    if($res){
      $this->status = 200;
      $this->message = "Success";
      $sess = $dt['sess'];

      $user = $this->bum->getById($dt['sess']->user->id);
      if (!is_object($sess)) {
          $sess = new stdClass();
      }
      if (!isset($sess->user)) {
          $sess->user = new stdClass();
      }
      $sess->user = $user; unset($user);
      $sess->user->menus = new stdClass();
      $sess->user->menus->left = array();

      $this->setKey($sess);

      $progress = $this->_checkDataProgress($sess->user, $cam->id);
      $capm = $this->capm->check($sess->user->id, $cam->id, 'Data Pribadi', 'data');
      if(isset($capm->id)){
        $this->capm->update($capm->id, $progress);
      }else{
        $progress['cdate'] = 'NOW()';
        $this->capm->set($progress);
      }

      $this->_setULog($sess->user->id, 13, 'pribadi');
    }else{
      $this->status = 900;
      $this->message = "Cannot update data to database";
    }

    $this->__json_out($data);
  }
}
