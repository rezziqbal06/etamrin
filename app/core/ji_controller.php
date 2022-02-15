<?php

class JI_Controller extends SENE_Controller
{
  public $user_login = 0;
  public $admin_login = 0;
  public $apikeys = 'kl17ie,21kl4ie';
  public $email_send = 1;
  public $wa_send = 0;
  public $is_log = 0;
  public $media_user = 'media/user/';
  public $status = 404;
  public $message = 'Not found';
  private $stepsRekrutmen = '';
  public $currentStepRekrutmen = '';
  private $dataProgress = '';
  private $viewDataProgress = '';
  private $viewDataTestAwal = '';
  private $viewDataPsikotest = '';

  public function __construct()
  {
    parent::__construct();
    $this->setTitle($this->config->semevar->site_name . ' ' . $this->config->semevar->site_suffix);
    $this->setDescription($this->config->semevar->site_description);
    $this->setKeyword($this->config->semevar->site_name);
    $this->setIcon(base_url('media/favicon/android-chrome-192x192x.png'));
    $this->setShortcutIcon(base_url('favicon.ico'));
    $this->stepsRekrutmen = array();

    $this->dataProgress = array();
    $this->viewDataProgress = array();
    $this->viewDataTestAwal = array();
    $this->viewDataPsikotest = array();
  }

  public function __init()
  {
    $data = array();
    $sess = $this->getKey();
    if (!is_object($sess)) {
      $sess = new stdClass();
    }
    if (!isset($sess->jobs)) {
      $sess->jobs = new stdClass();
      $sess->jobs->id = 0;
      $sess->jobs->referrer = '';
    }
    if (!isset($sess->user)) {
      $sess->user = new stdClass();
    }
    if (isset($sess->user->id)) {
      $this->user_login = 1;
    }

    if (!isset($sess->admin)) {
      $sess->admin = new stdClass();
    }
    if (isset($sess->admin->id)) {
      $this->admin_login = 1;
    }

    $data['sess'] = $sess;

    return $data;
  }

  /**
  * Apikey check procedure
  * @param  string   $apikey  String APIKEY
  * @return boolean           return 1 if valid, false otherwise
  */
  public function apikey_check($apikey)
  {
    if (strlen($apikey) > 4) {
      $apikeys = explode(',', $this->apikeys);
      if (in_array($apikey, $apikeys)) {
        return 1;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /**
  * Force download file
  * @param  [type] $pathFile [description]
  * @return [type]           [description]
  */
  protected function __forceDownload($pathFile)
  {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($pathFile));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($pathFile));
    ob_clean();
    flush();
    readfile($pathFile);
    exit;
  }

  /**
  * Check and Create directory for report temp
  * @param  string $periode        string with year/month format
  * @param  string $media_path     Default media_path location
  * @return string                 Return media path with current periode
  */
  protected function __checkDir($periode, $media_path = "media/laporan/")
  {
    if (!is_dir(SEMEROOT . 'media/')) mkdir(SEMEROOT . 'media/', 0777);
    if (!is_dir(SEMEROOT . $media_path)) mkdir(SEMEROOT . $media_path, 0777);
    $str = $periode . '/01';
    $periode_y = date("Y", strtotime($str));
    $periode_m = date("m", strtotime($str));
    if (!is_dir(SEMEROOT . $media_path . $periode_y)) mkdir(SEMEROOT . $media_path . $periode_y, 0777);
    if (!is_dir(SEMEROOT . $media_path . $periode_y . '/' . $periode_m)) mkdir(SEMEROOT . $media_path . $periode_y . '/' . $periode_m, 0777);
    return SEMEROOT . $media_path . $periode_y . '/' . $periode_m;
  }


  /**
  * Convert string to url friendly
  * @param  string $s    String
  * @return string       slug
  */
  protected function slugify($s)
  {
    // replace non letter or digits by -
    $s = preg_replace('~[^\pL\d]+~u', '-', $s);
    // transliterate
    $s = iconv('utf-8', 'us-ascii//TRANSLIT', $s);
    // remove unwanted characters
    $s = preg_replace('~[^-\w]+~', '', $s);
    // trim
    $s = trim($s, '-');
    // remove duplicate -
    $s = preg_replace('~-+~', '-', $s);
    // lowercase
    $s = strtolower($s);
    return $s;
  }

  /**
  * Output the json formatted string
  * @param  mixed $dt input object or array
  * @return string     sting json formatted with its header
  */
  public function __json_out($dt)
  {
    $this->lib('sene_json_engine', 'sene_json');
    $data = array();
    if (isset($_SERVER['SEME_MEMORY_VERBOSE'])) {
      $data["memory"] = round(memory_get_usage() / 1024 / 1024, 5) . " MBytes";
    }
    $data["status"]  = (int) $this->status;
    $data["message"] = $this->message;
    $data["data"]  = $dt;
    $this->sene_json->out($data);
    die();
  }


  public function __jsonDataTable($data, $count, $another = array())
  {
    $this->lib('sene_json_engine', 'sene_json');
    $rdata = array();
    if (!is_array($data)) {
      $data = array();
    }
    $dt1 = array();
    $dt2 = array();
    if (!is_array($data)) {
      trigger_error('jsonDataTable first params need array!');
      die();
    }
    foreach ($data as $dat) {
      $dt2 = array();
      if (is_int($dat)) {
        trigger_error('[ERROR: ' . $dat . '] Data table not well performed because a query execution error!');
      }
      foreach ($dat as $dt) {
        $dt2[] = $dt;
      }
      $dt1[] = $dt2;
    }

    if (is_array($another)) {
      $rdata = $another;
    }
    $rdata['data'] = $dt1;
    $rdata['recordsFiltered'] = $count;
    $rdata['recordsTotal'] = $count;
    $rdata['status'] = (int) $this->status;
    $rdata['message'] = $this->message;
    $this->sene_json->out($rdata);
    die();
  }


  public function __dateIndonesia($datetime, $utype = 'hari_tanggal')
  {
    $stt = strtotime($datetime);
    $bulan_ke = date('n', $stt);
    $bulan = 'Desember';
    switch ($bulan_ke) {
      case '1':
      $bulan = 'Januari';
      break;
      case '2':
      $bulan = 'Februari';
      break;
      case '3':
      $bulan = 'Maret';
      break;
      case '4':
      $bulan = 'April';
      break;
      case '5':
      $bulan = 'Mei';
      break;
      case '6':
      $bulan = 'Juni';
      break;
      case '7':
      $bulan = 'Juli';
      break;
      case '8':
      $bulan = 'Agustus';
      break;
      case '9':
      $bulan = 'September';
      break;
      case '10':
      $bulan = 'Oktober';
      break;
      case '11':
      $bulan = 'November';
      break;
      default:
      $bulan = 'Desember';
    }
    $hari_ke = date('N', $stt);
    $hari = 'Minggu';
    switch ($hari_ke) {
      case '1':
      $hari = 'Senin';
      break;
      case '2':
      $hari = 'Selasa';
      break;
      case '3':
      $hari = 'Rabu';
      break;
      case '4':
      $hari = 'Kamis';
      break;
      case '5':
      $hari = 'Jumat';
      break;
      case '6':
      $hari = 'Sabtu';
      break;
      default:
      $hari = 'Minggu';
    }
    $utype == strtolower($utype);
    if ($utype == "hari") {
      return $hari;
    }
    if ($utype == "jam") {
      return date('H:i', $stt) . ' WIB';
    }
    if ($utype == "tanggal") {
      return '' . date('d', $stt) . ' ' . $bulan . ' ' . date('Y', $stt);
    }
    if ($utype == "tanggal_jam") {
      return '' . date('d', $stt) . ' ' . $bulan . ' ' . date('Y H:i', $stt) . ' WIB';
    }
    if ($utype == "hari_tanggal") {
      return $hari . ', ' . date('d', $stt) . ' ' . $bulan . ' ' . date('Y', $stt);
    }
    if ($utype == "hari_tanggal_jam") {
      return $hari . ', ' . date('d', $stt) . ' ' . $bulan . ' ' . date('Y H:i', $stt) . ' WIB';
    }
    if ($utype == "bulan") {
      return $bulan;
    }
    if ($utype == "bulan_tahun") {
      return $bulan.' '.date('Y', $stt);;
    }
    if ($utype == "tahun") {
      return date('Y', $stt);
    }
  }


  //check allowed modules
  public function checkPermissionAdmin($a_modules_identifier)
  {
    $is_allowed = 0;
    $modules = array();
    $sess = $this->getKey();
    if (isset($sess->admin->modules)) {
      $modules = $sess->admin->modules;
    }
    if (isset($modules[$a_modules_identifier])) {
      $is_allowed = 1;
    }
    return $is_allowed;
  }

  /**
  * Output the json formatted string for select2
  * @param  mixed $dt input object or array
  * @return string     sting json formatted with its header
  */
  public function __json_select2($dt)
  {
    $this->lib('sene_json_engine', 'sene_json');
    $this->sene_json->out($dt);
    die();
  }

  /**
  * Global variable replacement for email templates
  */
  protected function _emailReplacer(){
    $replacer = array();
    $replacer['site_logo'] = $this->cdn_url($this->config->semevar->site_logo);
    $replacer['cs_email'] = $this->config->semevar->email_reply;
    $replacer['app_name'] = $this->config->semevar->app_name;
		$replacer['company_name'] = $this->config->semevar->app_name;
    $replacer['site_name'] = $this->config->semevar->app_name;
    $replacer['email_dari'] = $this->config->semevar->email_from;
		$replacer['email_reply'] = $this->config->semevar->email_reply;
    return $replacer;
  }

  /**
  * Text Masking
  */
  public function _textMasking($txt)
  {
    $o = 4;
    $l = strlen($txt);
    if(($l - $o) > 1){
      return substr_replace($txt, str_repeat('X', $l - $o), 0, $l - $o);
    }else{
      return substr_replace($txt, str_repeat('X', $l), 0, $l);
    }
  }

  /**
  * Procedure that check requires email confirmation is true
  */
  public function requiredVerifiedEmail($bum){
    if($this->config->semevar->email_strict && empty($bum->is_confirmed)){
      redir(base_url('kandidat/verifikasi/email'),0);
      return;
    }
  }

  /**
  * function get array key and values of progress rekrutmen
  */
  public function stepsRekrutmen(){
    $this->stepsRekrutmen = array(
      'apply'=> array('id'=>1,'text'=>'Apply', 'url'=>'joblist'),
      'tes_awal'=> array('id'=>2,'text'=>'Tes Awal', 'url'=>'#'),
      'update_data'=> array('id'=>3,'text'=>'Pembaruan Data', 'url'=>'#'),
      'psikotes'=> array('id'=>4,'text'=>'Psikotes', 'url'=>'#'),
      'psikotes_lolos'=> array('id'=>5,'text'=>'Interview HR', 'url'=>'#'),
      'interview_hr'=> array('id'=>6,'text'=>'Interview User', 'url'=>'#'),
      'selesai'=> array('id'=>9,'text'=>'Selesai', 'url'=>'#')
    );
    return $this->stepsRekrutmen;
  }

  /**
  * procedure that generates view of progress rekrutmen
  */
  public function progressRekrutmen($bum_apply_statno){
    $b = 0;
    $cs = 0;
    $a = '';
    $c = '';
    $s = '';
    $current_id = -1;

    $i = 0;
    $bum_apply_statno = (int) $bum_apply_statno;
    foreach($this->stepsRekrutmen() as $ksr=>$sr){
      if($bum_apply_statno >= $sr['id']){
        $b=$i+1;
        $c = 'completed';
      }else if($b == $i){
        $c = 'active';
        $b = -1;
      }else{
        $c = '';
      }
      $s .= '<div id="'.$ksr.'" class="stepper-item '.$c.'" data-url="'.$sr['url'].'">
      <div class="step-counter">'.($i+1).'</div>
      <div class="step-name">'.$sr['text'].'</div>
      </div>';
      if($b>=0){
        $b = $i+1;
      }
      $i++;
    }
    return $s;
  }

  /**
  * procedure for update recruitment progress
  * @param  integer $b_user_id        Current user id
  * @param  string  $stepkey          current progress step that want to update
  * @return void
  */
  public function _updateProgress($b_user_id, $c_apply_id, $stepkey){
    if(!isset($this->user_progress_model)) $this->load('api_front/c_apply_progress_model','user_progress_model');
    foreach($this->config->semevar->progress_rekrutmen as $k=>$v){
      $di = array(
        'b_user_id'=>$b_user_id,
        'c_apply_id'=>$c_apply_id,
        'stepkey'=>$k,
        'is_done'=>0,
      );
      $user_progress_model = $this->user_progress_model->check($b_user_id,$c_apply_id,$k);
      if(isset($user_progress_model->id)){
        if($user_progress_model->stepkey == $stepkey){
          $di['is_done'] = 1;
        }
        $this->user_progress_model->update($user_progress_model->id,$di);
      }else{
        $di['cdate'] = 'NOW()';
        $this->user_progress_model->set($di);
      }
    }
  }

  /**
  * Function for generated data tes awal for view
  */
  protected function _getViewTesAwal($testskill){
    $this->viewDataTestAwal['Tes CS'] = new stdClass();
    $this->viewDataTestAwal['Tes CS']->deskripsi = 'Tes untuk menentukan kemampuan dasar pelamar.';
    $this->viewDataTestAwal['Tes CS']->url = 'tes/cs';
    $this->viewDataTestAwal['Tes CS']->progressbar = 0;

    if(is_array($testskill) && count($testskill)){
      $i=0;
      foreach($testskill as $ts){
        $i++;
        $keyname = $ts->nama;

        $desk = substr(strip_tags($ts->ket),0,57);
        if(strlen($desk)>56){
          $desk = substr(strip_tags($ts->ket),0,53).'...';
        }

        $this->viewDataTestAwal[$keyname] = new stdClass();
        $this->viewDataTestAwal[$keyname]->deskripsi = $desk;
        $this->viewDataTestAwal[$keyname]->url = 'tes/skill/index/'.$ts->id;
        $this->viewDataTestAwal[$keyname]->progressbar = 0;
      }
    }

    return $this->viewDataTestAwal;
  }

  /**
  * Function for generated data tes awal for view
  */
  protected function _getViewPsikotest($b_user_id=''){

    $this->viewDataPsikotest['Tes Intelegensi'] = new stdClass();
    $this->viewDataPsikotest['Tes Intelegensi']->deskripsi = 'Tes untuk menentukan intelegensi pelamar.';
    $this->viewDataPsikotest['Tes Intelegensi']->url = 'tes/iq';
    $this->viewDataPsikotest['Tes Intelegensi']->progressbar = 0;

    $this->viewDataPsikotest['Tes Kepribadian'] = new stdClass();
    $this->viewDataPsikotest['Tes Kepribadian']->deskripsi = 'Tes untuk menentukan kepribadian pelamar.';
    $this->viewDataPsikotest['Tes Kepribadian']->url = 'tes/kepribadian';
    $this->viewDataPsikotest['Tes Kepribadian']->progressbar = 0;
    return $this->viewDataPsikotest;
  }

  /**
  * Function for generated data progress for view
  */
  protected function _getViewDataProgress($b_user_id=''){

    $this->viewDataProgress['Data Pribadi'] = new stdClass();
    $this->viewDataProgress['Data Pribadi']->deskripsi = 'Pembaruan data riwayat keluarga.';
    $this->viewDataProgress['Data Pribadi']->url = 'kandidat/profil';
    $this->viewDataProgress['Data Pribadi']->progressbar = 0;

    $this->viewDataProgress['Data Keluarga'] = new stdClass();
    $this->viewDataProgress['Data Keluarga']->deskripsi = 'Pembaruan data riwayat keluarga.';
    $this->viewDataProgress['Data Keluarga']->url = 'kandidat/riwayat/keluarga';
    $this->viewDataProgress['Data Keluarga']->progressbar = 0;

    $this->viewDataProgress['Riwayat Pekerjaan'] = new stdClass();
    $this->viewDataProgress['Riwayat Pekerjaan']->deskripsi = 'Pembaruan data riwayat pekerjaan.';
    $this->viewDataProgress['Riwayat Pekerjaan']->url = 'kandidat/riwayat/pekerjaan';
    $this->viewDataProgress['Riwayat Pekerjaan']->progressbar = 0;

    $this->viewDataProgress['Riwayat Pendidikan Formal'] = new stdClass();
    $this->viewDataProgress['Riwayat Pendidikan Formal']->deskripsi = 'Pembaruan data riwayat pendidikan formal.';
    $this->viewDataProgress['Riwayat Pendidikan Formal']->url = 'kandidat/riwayat/formal';
    $this->viewDataProgress['Riwayat Pendidikan Formal']->progressbar = 0;

    $this->viewDataProgress['Riwayat Pendidikan Non-Formal'] = new stdClass();
    $this->viewDataProgress['Riwayat Pendidikan Non-Formal']->deskripsi = 'Pembaruan data riwayat pendidikan nonformal.';
    $this->viewDataProgress['Riwayat Pendidikan Non-Formal']->url = 'kandidat/riwayat/informal';
    $this->viewDataProgress['Riwayat Pendidikan Non-Formal']->progressbar = 0;

    $this->viewDataProgress['Riwayat Organisasi &amp; Profesi'] = new stdClass();
    $this->viewDataProgress['Riwayat Organisasi &amp; Profesi']->deskripsi = 'Pembaruan data riwayat organisasi dan profesi.';
    $this->viewDataProgress['Riwayat Organisasi &amp; Profesi']->url = 'kandidat/riwayat/organisasi';
    $this->viewDataProgress['Riwayat Organisasi &amp; Profesi']->progressbar = 0;

    $this->viewDataProgress['Kemampuan Bahasa Asing'] = new stdClass();
    $this->viewDataProgress['Kemampuan Bahasa Asing']->deskripsi = 'Pembaruan data riwayat kemampuan bahasa asing.';
    $this->viewDataProgress['Kemampuan Bahasa Asing']->url = 'kandidat/skill/bahasa';
    $this->viewDataProgress['Kemampuan Bahasa Asing']->progressbar = 0;

    $this->viewDataProgress['Kemampuan Komputer'] = new stdClass();
    $this->viewDataProgress['Kemampuan Komputer']->deskripsi = 'Pembaruan data riwayat kemampuan komputer.';
    $this->viewDataProgress['Kemampuan Komputer']->url = 'kandidat/skill/komputer';
    $this->viewDataProgress['Kemampuan Komputer']->progressbar = 0;

    $this->viewDataProgress['Data Referensi &amp; Rekomendasi'] = new stdClass();
    $this->viewDataProgress['Data Referensi &amp; Rekomendasi']->deskripsi = 'Pembaruan data referensi dan rekomendasi.';
    $this->viewDataProgress['Data Referensi &amp; Rekomendasi']->url = 'kandidat/keterangan/referensi';
    $this->viewDataProgress['Data Referensi &amp; Rekomendasi']->progressbar = 0;

    $this->viewDataProgress['Data Kenalan'] = new stdClass();
    $this->viewDataProgress['Data Kenalan']->deskripsi = 'Data kenalan anda di '.$this->config->semevar->company_name_short.'.';
    $this->viewDataProgress['Data Kenalan']->url = 'kandidat/keterangan/kenalan';
    $this->viewDataProgress['Data Kenalan']->progressbar = 0;

    $this->viewDataProgress['Data Keterangan Lainnya'] = new stdClass();
    $this->viewDataProgress['Data Keterangan Lainnya']->deskripsi = 'Pembaruan data keterangan lainnya.';
    $this->viewDataProgress['Data Keterangan Lainnya']->url = 'kandidat/keterangan';
    $this->viewDataProgress['Data Keterangan Lainnya']->progressbar = 0;

    $this->viewDataProgress['Upload Data'] = new stdClass();
    $this->viewDataProgress['Upload Data']->deskripsi = 'Upload file persyaratan yang dibutuhkan.';
    $this->viewDataProgress['Upload Data']->url = 'kandidat/upload';
    $this->viewDataProgress['Upload Data']->progressbar = 0;
    return $this->viewDataProgress;
  }

  /**
  * Function for generated data progress for step progress
  */
  protected function _getDataProgress($b_user_id=''){
    $this->dataProgress = array();
    $this->dataProgress['Data Pribadi'] = array();
    $this->dataProgress['Data Pribadi']['b_user_id'] = $b_user_id;
    $this->dataProgress['Data Pribadi']['utype'] = 'data';
    $this->dataProgress['Data Pribadi']['ldate'] = 'NOW()';
    $this->dataProgress['Data Pribadi']['stepkey'] = 'Data Pribadi';
    $this->dataProgress['Data Pribadi']['from_val'] = 0;
    $this->dataProgress['Data Pribadi']['to_val'] = 0;
    $this->dataProgress['Data Pribadi']['is_done'] = 0;

    $this->dataProgress['Data Keluarga'] = array();
    $this->dataProgress['Data Keluarga']['b_user_id'] = $b_user_id;
    $this->dataProgress['Data Keluarga']['utype'] = 'data';
    $this->dataProgress['Data Keluarga']['ldate'] = 'NOW()';
    $this->dataProgress['Data Keluarga']['stepkey'] = 'Data Keluarga';
    $this->dataProgress['Data Keluarga']['from_val'] = 0;
    $this->dataProgress['Data Keluarga']['to_val'] = 0;
    $this->dataProgress['Data Keluarga']['is_done'] = 0;

    $this->dataProgress['Kemampuan Bahasa Asing'] = array();
    $this->dataProgress['Kemampuan Bahasa Asing']['b_user_id'] = $b_user_id;
    $this->dataProgress['Kemampuan Bahasa Asing']['utype'] = 'data';
    $this->dataProgress['Kemampuan Bahasa Asing']['ldate'] = 'NOW()';
    $this->dataProgress['Kemampuan Bahasa Asing']['stepkey'] = 'Kemampuan Bahasa Asing';
    $this->dataProgress['Kemampuan Bahasa Asing']['from_val'] = 0;
    $this->dataProgress['Kemampuan Bahasa Asing']['to_val'] = 0;
    $this->dataProgress['Kemampuan Bahasa Asing']['is_done'] = 0;

    $this->dataProgress['Riwayat Pekerjaan'] = array();
    $this->dataProgress['Riwayat Pekerjaan']['b_user_id'] = $b_user_id;
    $this->dataProgress['Riwayat Pekerjaan']['utype'] = 'data';
    $this->dataProgress['Riwayat Pekerjaan']['ldate'] = 'NOW()';
    $this->dataProgress['Riwayat Pekerjaan']['stepkey'] = 'Riwayat Pekerjaan';
    $this->dataProgress['Riwayat Pekerjaan']['from_val'] = 0;
    $this->dataProgress['Riwayat Pekerjaan']['to_val'] = 0;
    $this->dataProgress['Riwayat Pekerjaan']['is_done'] = 0;

    $this->dataProgress['Riwayat Organisasi &amp; Profesi'] = array();
    $this->dataProgress['Riwayat Organisasi &amp; Profesi']['b_user_id'] = $b_user_id;
    $this->dataProgress['Riwayat Organisasi &amp; Profesi']['utype'] = 'data';
    $this->dataProgress['Riwayat Organisasi &amp; Profesi']['ldate'] = 'NOW()';
    $this->dataProgress['Riwayat Organisasi &amp; Profesi']['stepkey'] = 'Riwayat Organisasi &amp; Profesi';
    $this->dataProgress['Riwayat Organisasi &amp; Profesi']['from_val'] = 0;
    $this->dataProgress['Riwayat Organisasi &amp; Profesi']['to_val'] = 0;
    $this->dataProgress['Riwayat Organisasi &amp; Profesi']['is_done'] = 0;

    $this->dataProgress['Riwayat Pendidikan Formal'] = array();
    $this->dataProgress['Riwayat Pendidikan Formal']['b_user_id'] = $b_user_id;
    $this->dataProgress['Riwayat Pendidikan Formal']['utype'] = 'data';
    $this->dataProgress['Riwayat Pendidikan Formal']['ldate'] = 'NOW()';
    $this->dataProgress['Riwayat Pendidikan Formal']['stepkey'] = 'Riwayat Pendidikan Formal';
    $this->dataProgress['Riwayat Pendidikan Formal']['from_val'] = 0;
    $this->dataProgress['Riwayat Pendidikan Formal']['to_val'] = 0;
    $this->dataProgress['Riwayat Pendidikan Formal']['is_done'] = 0;

    $this->dataProgress['Riwayat Pendidikan Non-Formal'] = array();
    $this->dataProgress['Riwayat Pendidikan Non-Formal']['b_user_id'] = $b_user_id;
    $this->dataProgress['Riwayat Pendidikan Non-Formal']['utype'] = 'data';
    $this->dataProgress['Riwayat Pendidikan Non-Formal']['ldate'] = 'NOW()';
    $this->dataProgress['Riwayat Pendidikan Non-Formal']['stepkey'] = 'Riwayat Pendidikan Non-Formal';
    $this->dataProgress['Riwayat Pendidikan Non-Formal']['from_val'] = 0;
    $this->dataProgress['Riwayat Pendidikan Non-Formal']['to_val'] = 0;
    $this->dataProgress['Riwayat Pendidikan Non-Formal']['is_done'] = 0;

    $this->dataProgress['Kemampuan Bahasa Asing'] = array();
    $this->dataProgress['Kemampuan Bahasa Asing']['b_user_id'] = $b_user_id;
    $this->dataProgress['Kemampuan Bahasa Asing']['utype'] = 'data';
    $this->dataProgress['Kemampuan Bahasa Asing']['ldate'] = 'NOW()';
    $this->dataProgress['Kemampuan Bahasa Asing']['stepkey'] = 'Kemampuan Bahasa Asing';
    $this->dataProgress['Kemampuan Bahasa Asing']['from_val'] = 0;
    $this->dataProgress['Kemampuan Bahasa Asing']['to_val'] = 0;
    $this->dataProgress['Kemampuan Bahasa Asing']['is_done'] = 0;

    $this->dataProgress['Kemampuan Komputer'] = array();
    $this->dataProgress['Kemampuan Komputer']['b_user_id'] = $b_user_id;
    $this->dataProgress['Kemampuan Komputer']['utype'] = 'data';
    $this->dataProgress['Kemampuan Komputer']['ldate'] = 'NOW()';
    $this->dataProgress['Kemampuan Komputer']['stepkey'] = 'Kemampuan Komputer';
    $this->dataProgress['Kemampuan Komputer']['from_val'] = 0;
    $this->dataProgress['Kemampuan Komputer']['to_val'] = 0;
    $this->dataProgress['Kemampuan Komputer']['is_done'] = 0;

    $this->dataProgress['Data Referensi &amp; Rekomendasi'] = array();
    $this->dataProgress['Data Referensi &amp; Rekomendasi']['b_user_id'] = $b_user_id;
    $this->dataProgress['Data Referensi &amp; Rekomendasi']['utype'] = 'data';
    $this->dataProgress['Data Referensi &amp; Rekomendasi']['ldate'] = 'NOW()';
    $this->dataProgress['Data Referensi &amp; Rekomendasi']['stepkey'] = 'Data Referensi &amp; Rekomendasi';
    $this->dataProgress['Data Referensi &amp; Rekomendasi']['from_val'] = 0;
    $this->dataProgress['Data Referensi &amp; Rekomendasi']['to_val'] = 0;
    $this->dataProgress['Data Referensi &amp; Rekomendasi']['is_done'] = 0;

    $this->dataProgress['Data Kenalan'] = array();
    $this->dataProgress['Data Kenalan']['b_user_id'] = $b_user_id;
    $this->dataProgress['Data Kenalan']['utype'] = 'data';
    $this->dataProgress['Data Kenalan']['ldate'] = 'NOW()';
    $this->dataProgress['Data Kenalan']['stepkey'] = 'Data Referensi &amp; Rekomendasi';
    $this->dataProgress['Data Kenalan']['from_val'] = 0;
    $this->dataProgress['Data Kenalan']['to_val'] = 0;
    $this->dataProgress['Data Kenalan']['is_done'] = 0;

    $this->dataProgress['Data Keterangan Lainnya'] = array();
    $this->dataProgress['Data Keterangan Lainnya']['b_user_id'] = $b_user_id;
    $this->dataProgress['Data Keterangan Lainnya']['utype'] = 'data';
    $this->dataProgress['Data Keterangan Lainnya']['ldate'] = 'NOW()';
    $this->dataProgress['Data Keterangan Lainnya']['stepkey'] = 'Data Keterangan Lainnya';
    $this->dataProgress['Data Keterangan Lainnya']['from_val'] = 0;
    $this->dataProgress['Data Keterangan Lainnya']['to_val'] = 0;
    $this->dataProgress['Data Keterangan Lainnya']['is_done'] = 0;
    return $this->dataProgress;
  }

  /**
  * Function for return value of CS test result
  * if score has filled
  * should return the iq test result
  */
  public function _resultTestCS($score=''){
    $hasil = array();
    $i=0;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 4;
    $hasil[$i]->cs = 1;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 10;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 5;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 4;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 0;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->next = 0;
    $hasil[$i]->cs = $hasil[($i-1)]->cs+$hasil[($i-1)]->next;

    if(strlen($score)){
      $score = (int) $score;
      if($score<=0) $score = 0;
      return isset($hasil[$score]) ? $hasil[$score] : $hasil;
    }else{
      return $hasil;
    }
  }

  /**
  * Function for return value of IQ test result
  * if score has filled
  * should return the iq test result
  */
  public function _resultTestIQ($score=''){
    $hasil = array();
    $i=0;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = 36;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan yang sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sudah pasti lemah';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Diambang Kekurangan';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Diambang Kekurangan';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Diambang Kekurangan';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Diambang Kekurangan';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Diambang Kekurangan';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Di bawah rata2';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Di bawah rata2';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Di bawah rata2';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Di bawah rata2';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Di bawah rata2';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Normal';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Kecerdasan Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Sangat Superior';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 2;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 4;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 4;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 5;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 5;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 5;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 1;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    $i++;
    $hasil[$i] = new stdClass();
    $hasil[$i]->nama = 'Genius';
    $hasil[$i]->next = 0;
    $hasil[$i]->iq = $hasil[($i-1)]->iq+$hasil[($i-1)]->next;

    if(strlen($score)){
      $score = (int) $score;
      if($score<=0) $score = 0;
      return isset($hasil[$score]) ? $hasil[$score] : $hasil;
    }else{
      return $hasil;
    }
  }

  protected function validasiStatusApply($bum,$apply_statno_min=0,$apply_statno_max=0,$is_redirect=1){
    if(isset($bum->apply_statno)){
      if($bum->apply_statno>=$apply_statno_min && $bum->apply_statno<=$apply_statno_max){
        if($is_redirect){

        }else{
          return true;
        }
      }else{
        if($is_redirect){

        }else{
          return false;
        }
      }
    }
    if($is_redirect){
      redir(base_url('noaccess'));
    }else{
      return false;
    }
  }protected function __flash($message='',$type='info'){
    $s = $this->getKey();
    if(!is_object($s)) $s = new stdClass();
    if(!isset($s->flash)) $s->flash = '';
    if(strlen($message)>0){
      $s->flash = $message;
    }
    $this->setKey($s);
    return $s;
  }
  protected function __flashClear(){
    $s = $this->getKey();
    if(!is_object($s)) $s = new stdClass();
    if(!isset($s->flash)) $s->flash = '';
    $s->flash = '';
    $this->setKey($s);
    return $s;
  }
  
  protected function _setULog($b_user_id,$a_itemlog_id,$keterangan='NULL'){
    if(!empty($b_user_id) && !empty($a_itemlog_id) && isset($this->config->semevar->is_user_log) && !empty($this->config->semevar->is_user_log)){
      $this->load('b_user_log_model','ulog');
      $this->ulog->set(array('cdate' => date('Y-m-d H:i:s'), 'b_user_id' => $b_user_id, 'a_itemlog_id' => $a_itemlog_id, 'keterangan' => $keterangan));
    }
  }

  public function index()
  {
  }
}
