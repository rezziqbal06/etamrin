<?php
/**
* API_Front/kandidat
*/
class Keluarga extends JI_Controller {
  var $currentDataProgress = 'Data Keluarga';

  public function __construct()
  {
    parent::__construct();
    $this->lib("seme_log");
    $this->lib('seme_email');
    $this->load("api_front/a_usermodule_model", "aum");
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/b_user_jobhistory_model", 'bupdm');
    $this->load("api_front/b_user_pendidikan_model", 'bupdm');
    $this->load("api_front/b_user_keluarga_model", 'bukgm');
    $this->load("api_front/b_user_usermodule_model", "buum");
    $this->load("api_front/c_apply_model", 'cam');
    $this->load("api_front/c_apply_progress_model", 'capm');
  }

  private function _checkData(){
    return array();
  }

  private function _checkDataProgress($bum, $c_apply_id){
    $progress = array();
    $progress['b_user_id'] = $bum->id;
    $progress['c_apply_id'] = $c_apply_id;
    $progress['utype'] = 'data';
    $progress['ldate'] = 'NOW()';
    $progress['stepkey'] = $this->currentDataProgress;
    $progress['from_val'] = 0;
    $progress['to_val'] = 0;
    $progress['is_done'] = 0;

    if(strlen($bum->status_kawin)>1){
      $progress['from_val']++;
    }
    // ibu
    $progress['to_val']++;
    // bapak
    $progress['to_val']++;
    $progress['to_val'] += (int) $bum->saudara_dari;

    if($bum->status_kawin != 'Lajang'){
      // istri
      $progress['to_val']++;
      $progress['to_val'] += (int) $bum->jml_anak;
    }

    return $progress;
  }

  public function index()
  {
    $dt = $this->__init();
    $data = array();
    $this->status=200;
    $this->message = 'Berhasil';
    $data=$this->bukgm->getByUserId($dt['sess']->user->id,'Formal');
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
      return;
    }

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
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

    $jenjang = $this->input->post('jenjang','');
    $nama = $this->input->post('nama','');
    $jurusan = $this->input->post('jurusan','');
    $lokasi = $this->input->post('lokasi','');
    $tahun_mulai = $this->input->post('tahun_mulai','');
    $tahun_selesai = $this->input->post('tahun_selesai','');
    $nilai = $this->input->post('nilai','');
    $keterangan = $this->input->post('keterangan','');
    $sumber_dana = $this->input->post('sumber_dana','');

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

    if(strlen($tahun_selesai) != 4){
      $this->status = 1104;
      $this->message = 'Tahun selesai harus diisi';
      $this->__json_out($data);
    }

    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = ucwords(strip_tags($v));
    }
    $di['b_user_id'] = $dt['sess']->user->id;
    $di['utype'] = 'Formal';
    $res = $this->bukgm->set($di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
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
      return;
    }
    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->bukgm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($data->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      return;
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
      return;
    }

    $bum = $this->bum->getById($dt['sess']->user->id);
    if(!isset($bum->id)){
      $this->status = 402;
      $this->message = 'Invalid User ID';
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

    $bukgm = $this->bukgm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($bukgm->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
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
    $sumber_dana = $this->input->post('sumber_dana','');

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

    if(strlen($tahun_selesai) != 4){
      $this->status = 1104;
      $this->message = 'Tahun selesai harus diisi';
      $this->__json_out($data);
    }

    $di = $_POST;
    foreach($di as $k=>$v){
      $di[$k] = ucwords(strip_tags($v));
    }
    $di['b_user_id'] = $dt['sess']->user->id;

    $res = $this->bukgm->update($id,$di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
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
      return;
    }
    if(isset($bum->is_edit_disabled) && !empty($bum->is_edit_disabled)){
      $this->status = 944;
      $this->message = 'Maaf! Pada tahap ini Anda sudah tidak bisa hapus / edit data lagi';
      $this->__json_out($data);
      return;
    }

    $this->status = 200;
    $this->message = 'Berhasil';

    $data = $this->bukgm->getByIdUserId($id,$dt['sess']->user->id);
    if(!isset($data->id)){
      $this->status = 1114;
      $this->message = 'This data not belong to this user';
      $this->__json_out($data);
      return;
    }

    $res = $this->bukgm->del($id);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
    }else{
      $this->status = 989;
      $this->message = 'Gagal menghapus data dari database';
    }
    $this->__json_out($data);
  }

  public function update(){
    $dt = $this->__init();

    $data = array();

    if(!isset($dt['sess']->user->id)){
			$this->status = 401;
			$this->message = 'Missing or invalid user';
			$this->__json_out($data);
			return;
    }

		//check apisess
		$bum = $this->bum->getById($dt['sess']->user->id);
		if(!isset($bum->id)){
			$this->status = 401;
			$this->message = 'Missing or invalid user';
			$this->__json_out($data);
			return;
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
    if(!is_array($nama)) $nama = array();
    if(count($nama)==0){
      $this->status = 989;
      $this->message = 'tidak ada data yang dikirim';
      $this->__json_out($data);
    }

    $progress = $this->_checkDataProgress($dt['sess']->user, $cam->id);
    $progress['from_val'] = 0;
    $progress['to_val'] = 0;

    $utype = $this->input->post('utype','');
    if(!is_array($utype)) $utype = array();
    $usia = $this->input->post('usia','');
    if(!is_array($usia)) $usia = array();
    $jk = $this->input->post('jk','');
    if(!is_array($jk)) $jk = array();
    $telp = $this->input->post('telp','');
    if(!is_array($telp)) $telp = array();
    $pekerjaan = $this->input->post('pekerjaan','');
    if(!is_array($pekerjaan)) $pekerjaan = array();
    $pendidikan = $this->input->post('pendidikan','');
    if(!is_array($pendidikan)) $pendidikan = array();
    $is_kandung = $this->input->post('is_kandung','');
    if(!is_array($is_kandung)) $is_kandung = array();

    $dis = array();
    $saudara_counter = 0;
    $anak_counter = 0;
    foreach($nama as $k=>$n){
      $di = array();
      $di['utype'] = isset($utype[$k]) ? strtolower(strip_tags($utype[$k])) : '';
      if($di['utype'] == 'anak'){
        $anak_counter++;
        continue;
      }
      if($di['utype'] == 'saudara'){
        $saudara_counter++;
        continue;
      }

      $di['b_user_id'] = $dt['sess']->user->id;
      $di['is_kandung'] = isset($is_kandung[$k]) ? strip_tags($is_kandung[$k]) : '1';
      $di['nama'] = isset($nama[$k]) ? strip_tags($nama[$k]) : '';
      $di['telp'] = isset($telp[$k]) ? strip_tags($telp[$k]) : '';
      $di['pekerjaan'] = isset($pekerjaan[$k]) ? strip_tags($pekerjaan[$k]) : '';
      $di['pendidikan'] = isset($pendidikan[$k]) ? strip_tags($pendidikan[$k]) : '';
      $di['usia'] = isset($usia[$k]) ? strip_tags($usia[$k]) : 'NULL';
      $di['usia'] = (int) $di['usia'];
      if($di['usia']<=0) $di['usia']='NULL';

      if($di['utype'] == 'istri'){
        $di['jk'] = '0';
      }elseif($di['utype'] == 'suami'){
        $di['jk'] = '1';
      }elseif($di['utype'] == 'ibu'){
        $di['jk'] = '0';
      }elseif($di['utype'] == 'ayah'){
        $di['jk'] = '1';
      }else{
        $di['jk'] = isset($jk[$k]) ? strip_tags($jk[$k]) : 'NULL';
      }

      $progress['to_val']++;
      if(strlen($di['nama'])){
        $progress['from_val']++;
      }
      $progress['to_val']++;
      if(strlen($di['usia'])){
        $progress['from_val']++;
      }
      $progress['to_val']++;
      if(strlen($di['jk'])){
        $progress['from_val']++;
      }

      $bukgm = $this->bukgm->check($dt['sess']->user->id,$di['utype']);
      if(isset($bukgm->id)){
        $this->bukgm->update($bukgm->id, $di);
      }else{
        $this->bukgm->set($di);
      }
    }

    //delete first and then always insert
    if($saudara_counter>0) $this->bukgm->delSaudaraByUserId($dt['sess']->user->id);
    if($anak_counter>0) $this->bukgm->delAnakByUserId($dt['sess']->user->id);
    foreach($nama as $k=>$n){
      $di = array();
      $di['utype'] = isset($utype[$k]) ? strtolower(strip_tags($utype[$k])) : '';
      if($di['utype'] == 'anak' || $di['utype'] == 'saudara'){
        $di['b_user_id'] = $dt['sess']->user->id;
        $di['is_kandung'] = isset($is_kandung[$k]) ? strip_tags($is_kandung[$k]) : '1';
        $di['nama'] = isset($nama[$k]) ? strip_tags($nama[$k]) : '';
        $di['telp'] = isset($telp[$k]) ? strip_tags($telp[$k]) : '';
        $di['pekerjaan'] = isset($pekerjaan[$k]) ? strip_tags($pekerjaan[$k]) : '';
        $di['pendidikan'] = isset($pendidikan[$k]) ? strip_tags($pendidikan[$k]) : '';
        $di['jk'] = isset($jk[$k]) ? strip_tags($jk[$k]) : 'NULL';
        $di['usia'] = isset($usia[$k]) ? strip_tags($usia[$k]) : 'NULL';
        $di['usia'] = (int) $di['usia'];
        if($di['usia']<=0) $di['usia']='NULL';

        $progress['to_val']++;
        if(strlen($di['nama'])){
          $progress['from_val']++;
        }
        $progress['to_val']++;
        if(strlen($di['jk'])){
          $progress['from_val']++;
        }

        $this->bukgm->set($di);
      }
    }

    $capm = $this->capm->check($dt['sess']->user->id, $cam->id, 'Data Keluarga', 'data');
    if(isset($capm->id)){
      $this->capm->update($capm->id, $progress);

    }else{
      $progress['cdate'] = 'NOW()';
      $this->capm->set($progress);
    }

    $this->_setULog($dt['sess']->user->id, 13, 'keluarga');

    $this->status = 200;
    $this->message = 'Berhasil';
    $this->__json_out($data);
  }

  public function status_kawin(){
    $dt = $this->__init();

    $data = array();

    if(!isset($dt['sess']->user->id)){
			$this->status = 401;
			$this->message = 'Missing or invalid user';
			$this->__json_out($data);
			return;
    }

		//check apisess
		$bum = $this->bum->getById($dt['sess']->user->id);
		if(!isset($bum->id)){
			$this->status = 401;
			$this->message = 'Missing or invalid user';
			$this->__json_out($data);
			return;
		}

    $du = array();
    $du['status_kawin'] = strip_tags($this->input->post('status_kawin',''));
    $du['saudara_ke'] = (int) strip_tags($this->input->post('saudara_ke','1'));
    $du['saudara_dari'] = (int) strip_tags($this->input->post('saudara_dari','1'));
    $du['jml_anak'] = (int) strip_tags($this->input->post('jml_anak',''));
    if($du['saudara_ke'] > $du['saudara_dari']){
      $this->status = 1087;
      $this->message = 'Silakan periksa lagi "Saya anak ke-" atau "Dari ... Bersaudara"';
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

    $progress = $this->_checkDataProgress($bum, $cam->id);
    $capm = $this->capm->check($dt['sess']->user->id, $cam->id, 'Data Keluarga', 'data');
    if(isset($capm->id)){
      $this->capm->update($capm->id, $progress);
    }else{
      $progress['cdate'] = 'NOW()';
      $this->capm->set($progress);
    }
    if(strlen($du['status_kawin']) <= 3){
			$this->status = 1013;
			$this->message = 'Invalid status kawin';
			$this->__json_out($data);
			return;
    }
    $res = $this->bum->update($dt['sess']->user->id, $du);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';

      $dt['sess']->user->status_kawin = $du['status_kawin'];
      $dt['sess']->user->saudara_ke = $du['saudara_ke'];
      $dt['sess']->user->saudara_dari = $du['saudara_dari'];
      $dt['sess']->user->jml_anak = $du['jml_anak'];
      $this->setKey($dt['sess']);
      $progress = $this->_checkDataProgress($dt['sess']->user, $cam->id);
      $capm = $this->capm->check($dt['sess']->user->id, $cam->id, 'Data Keluarga', 'data');
      if(isset($capm->id)){
        unset($progress['to_val']);
        $this->capm->update($capm->id, $progress);
      }else{
        $progress['cdate'] = 'NOW()';
        $this->capm->set($progress);
      }
    }else{
      $this->status = 989;
      $this->message = 'Gagal menghapus data dari database';
    }
    $this->__json_out($data);
  }

}
