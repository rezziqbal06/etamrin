<?php
class Joblist extends JI_Controller
{
  public $email_send = 1;

  public function __construct()
  {
    parent::__construct();
    $this->load("api_front/a_company_model", 'acm');
    $this->load("api_front/a_referrer_model", 'arm');
    $this->load("api_front/a_jabatan_model", 'ajm');
    $this->load("api_front/b_lowongan_model", 'blm');
    $this->load("api_front/b_lowongan_view_model", 'blvm');
    $this->load("api_front/b_user_model", 'bum');
    $this->load("api_front/c_apply_model", 'cam');
    $this->lib("seme_upload", 'su');
  }

  private function _sendEmailVerificationCode($res){
    require_once(SEMEROOT.'app/controller/api_front/register.php');
    $r = new Register();

    if ($this->email_send && strlen($res->email)>4 && empty($res->is_confirmed) && strlen($res->fb_id)<=1 && strlen($res->google_id)<=1) {
      $replacer = $this->_emailReplacer();
      $replacer['fnama'] = $res->fnama;
      $replacer['activation_code'] = $r->__activateGenerateLink($res->id,  $r->__genRegKode($res->id, $api_reg_token=''));
      $r->__userEmailVerification($res->email,$res->fnama,$replacer);
    }
  }

  public function index()
  {
    $d = $this->__init();
    $data = array();

    $draw = $this->input->post("draw");
    $sSearch = $this->input->request("search");
    $page = $this->input->post("page");
    $pagesize = $this->input->request("iDisplayLength");


    $is_active = $this->input->post('is_active');
    if (empty($is_active)) $is_active = "";

    if (empty($draw)) {
      $draw = 0;
    }
    if (empty($pagesize)) {
      $pagesize = 10;
    }
    if (empty($page)) {
      $page = 0;
    }

    $keyword = $sSearch;
    $sdate = $this->input->post("sdate");
    $edate = $this->input->post("edate");
    if (empty($sdate)) $sdate = "";
    if (empty($edate)) $edate = "";

    $sdate = date('Y-m-d 00:00:00');
    $edate = date('Y-m-d 23:59:59');

    $a_jabatan_ids = $this->input->post("a_jabatan_id");
    $min_exp = $this->input->post("min_exp");
    $min_pendidikans = $this->input->post("min_pendidikan");
    $lok_area = $this->input->post("lok_area");
    $max_usia = $this->input->post("max_usia");

    if (empty($min_exp)) $min_exp = "";
    if (empty($max_usia)) $max_usia = "";
    if (empty($min_pendidikans)) $min_pendidikans = array();
    if (empty($a_jabatan_ids)) $a_jabatan_ids = array();
    if (empty($lok_area)) $lok_area = array();

    $this->status = 200;
    $this->message = 'Berhasil';
    $dcount = $this->blm->countAll($sdate, $edate, $keyword, $a_jabatan_ids, $min_exp, $min_pendidikans, $max_usia, $lok_area);
    $ddata = $this->blm->getAll($page, $pagesize, $sdate, $edate, $keyword, $a_jabatan_ids, $min_exp, $min_pendidikans, $max_usia, $lok_area);

    foreach ($ddata as &$gd) {
      $awal = date_create();
      $akhir = date_create($gd->edate);
      $diff = date_diff($awal, $akhir);
      $gd->expired = $diff->y ? $diff->y . ' tahun ' : '';
      $gd->expired .= $diff->m ? $diff->m . ' bulan ' : '';
      $gd->expired .= $diff->d ? $diff->d . ' hari ' : '';
      $gd->expired .= !$diff->d ? 'Hari ini terakhir' : ' lagi';

      if (isset($gd->sdate)) $gd->sdate = $this->__dateIndonesia($gd->sdate);
      if (isset($gd->edate)) $gd->edate = $this->__dateIndonesia($gd->edate);
      if (isset($gd->ttype)) {
        if ($gd->ttype == 'penuh') {
          $gd->ttype = 'Full Time';
        } else {
          $gd->ttype = 'Part Time';
        }
      }
    }

    $data['halaman_saat_ini'] = $page;
    $data['total'] = $dcount;
    $data['total_halaman'] = ceil($dcount / $pagesize);
    $data['lowongan'] = $ddata;
    $this->__json_out($data);
  }

  public function check($id=''){
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 401;
      $this->message = 'Harus login, silakan login / daftar dulu';
      $this->__json_out($data);
      return;
    }
    $now = strtotime('now');
    $b_user_id = $d['sess']->user->id;

    $data['clm'] = $this->cam->check($b_user_id);
    if(isset($data['clm']->id)){
      if($data['clm']->is_process){
        $this->status = 700;
        $this->message = 'Belum bisa melamar karena ada lamaran yang masih diproses';
        $this->__json_out($data);
        return;
      }else{
        if($now > strtotime($data['clm']->edate)){
          $this->status = 701;
          $this->message = 'Lamaran anda sebelumnya telah gagal, anda bisa melamar setelah '.$this->__dateIndonesia($data['clm']->edate,'tanggal');
          $this->__json_out($data);
          return;
        }
      }
    }

    $id = (int) $id;
    if ($id<=0) {
      $this->status = 506;
      $this->message = 'ID Tidak valid';
      $this->__json_out($data);
      die();
    }

    $data['blm'] = $this->blm->getById($id);
    if(!isset($data['blm']->id)){
      $this->status = 570;
      $this->message = 'ID data lowongan tidak ditemukan';
      $this->__json_out($data);
      return;
    }

    if(strtotime($data['blm']->edate > $now)){
      $this->status = 701;
      $this->message = 'Lowongan ini telah ditutup';
      $this->__json_out($data);
      return;
    }

    $is_failed = 0;
    $status_teks = '';

    $data['acm'] = $this->acm->getById($data['blm']->a_company_id);
    $data['ajm'] = $this->ajm->getById($data['blm']->a_jabatan_id);

    //check batas umur
    $f = new DateTime($d['sess']->user->bdate);
    $t = new DateTime('today');
    if($f->diff($t)->y > intval($data['blm']->max_usia)){
      $status_teks = 'Tidak lolos: Usia, ';
      $is_failed = 1;
    }
    unset($f);

    //check min pendidikan
    $x = 0;
    $pm = $data['blm']->min_pendidikan;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $x = $this->config->semevar->pendidikans[$pm];
    }
    $y = 0;
    $pm = $d['sess']->user->pendidikan_terakhir;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $y = $this->config->semevar->pendidikans[$pm];
    }
    if($x > $y){
      $status_teks .= 'Tidak lolos: Min. Pendidikan, ';
      $is_failed = 1;
    }

    //check min pendidikan
    $x = 0;
    $pm = $data['blm']->min_pendidikan;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $x = $this->config->semevar->pendidikans[$pm];
    }
    $y = 0;
    $pm = $d['sess']->user->pendidikan_terakhir;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $y = $this->config->semevar->pendidikans[$pm];
    }
    if($x > $y){
      $status_teks .= 'Tidak lolos: Min. Pendidikan, ';
      $is_failed = 1;
    }

    //check min pengalaman kerja
    $x = (int) $data['blm']->min_exp;
    $y = (int) $d['sess']->user->kerja_exp_y;
    if(!empty($data['blm']->is_freshg)) $y = $x+1;
    if($x > $y){
      $status_teks .= 'Tidak lolos: Min. Pengalaman Kerja, ';
      $is_failed = 1;
    }
    if($is_failed){
      $this->status = 234;
      $this->message = 'Maaf, anda tidak memenuhi kriteria untuk melamar lowongan ini.<br> Reason: '.$status_teks;
      $this->__json_out(array('alasan'=>$status_teks));
      return;
    }

    $this->proses($id);
  }

  public function proses($id=''){
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 401;
      $this->message = 'Harus login, silakan login / daftar dulu';
      $this->__json_out($data);
      return;
    }
    $now = strtotime('now');
    $b_user_id = $d['sess']->user->id;

    $cam = $this->cam->checkByUserId($d['sess']->user->id);
    if(is_array($cam) && count($cam)){
      foreach($cam as $ca){
        if(empty($ca->is_process) && !empty($ca->is_failed)) {
          $this->status = 700;
          $this->message = 'Anda telah dinyatakan tidak lolos';
          if(!is_null($ca->edate)){
            $this->message .= ', anda bisa kembali apply setelah '.$this->__dateIndonesia($ca->edate,'tanggal');
          }
          $this->__json_out($data);
          return;
        }elseif(!empty($ca->is_process) && empty($ca->is_failed)){
          $this->status = 701;
          $this->message = 'Belum bisa apply lagi, karena masih dalam proses seleksi';
          $this->__json_out($data);
          return;
        }elseif(!empty($ca->is_process) && empty($ca->is_failed)){
          $this->status = 702;
          $this->message = 'Anda sudah lolos hasil seleksi';
          $this->__json_out($data);
          return;
        }
      }
    }

    $id = (int) $id;
    if ($id<=0) {
      $this->status = 506;
      $this->message = 'ID Tidak valid';
      $this->__json_out($data);
      die();
    }

    $data['blm'] = $this->blm->getById($id);
    if(!isset($data['blm']->id)){
      $this->status = 570;
      $this->message = 'ID data lowongan tidak ditemukan';
      $this->__json_out($data);
      return;
    }

    if(strtotime($data['blm']->edate > $now)){
      $this->status = 701;
      $this->message = 'Lowongan ini telah ditutup';
      $this->__json_out($data);
      return;
    }
    $is_failed = 0;
    $status_teks = '';

    $data['acm'] = $this->acm->getById($data['blm']->a_company_id);
    $data['ajm'] = $this->ajm->getById($data['blm']->a_jabatan_id);

    //check batas umur
    $f = new DateTime($d['sess']->user->bdate);
    $t = new DateTime('today');
    if($f->diff($t)->y > $data['blm']->max_usia){
      $status_teks = 'Tidak lolos: Usia, ';
      $is_failed = 1;
    }
    unset($f);

    //check min pendidikan
    $x = 0;
    $pm = $data['blm']->min_pendidikan;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $x = $this->config->semevar->pendidikans[$pm];
    }
    $y = 0;
    $pm = $d['sess']->user->pendidikan_terakhir;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $y = $this->config->semevar->pendidikans[$pm];
    }
    if($x > $y){
      $status_teks .= 'Tidak lolos: Min. Pendidikan, ';
      $is_failed = 1;
    }

    //check min pendidikan
    $x = 0;
    $pm = $data['blm']->min_pendidikan;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $x = $this->config->semevar->pendidikans[$pm];
    }
    $y = 0;
    $pm = $d['sess']->user->pendidikan_terakhir;
    if(isset($this->config->semevar->pendidikans[$pm])){
      $y = $this->config->semevar->pendidikans[$pm];
    }
    if($x > $y){
      $status_teks .= 'Tidak lolos: Min. Pendidikan, ';
      $is_failed = 1;
    }

    //check min pengalaman kerja
    $x = (int) $data['blm']->min_exp;
    $y = (int) $d['sess']->user->kerja_exp_y;
    if(!empty($data['blm']->is_freshg)) $y = $x+1;
    if($x > $y){
      $status_teks .= 'Tidak lolos: Min. Pengalaman Kerja, ';
      $is_failed = 1;
    }

    //add expired date
    $edate = 'NULL';
    $is_process = 1;
    $status_last = 'Melengkapi Dokumen';
    $edate = $t->add(new DateInterval('P'.$this->config->semevar->expire_date_after_apply.'D'));
    $edate = $edate->format('Y-m-d');

    if($is_failed){
      $is_process = 0;
      $status_last = 'Tidak Lolos';
    }

    //check referrer
    $referrer = '';
    if(isset($d['sess']->jobs->referrer)){
      $referrer = $d['sess']->jobs->referrer;
    }
    if(strlen($referrer)){
      $arm = $this->arm->search($referrer);
      if(isset($arm->referrer)){
        $referrer = $arm->referrer;
      }
    }
    if(empty($referrer)) $referrer = 'direct';

    $di = array(
      'b_user_id' => $b_user_id,
      'b_lowongan_id' => $id,
      'status_last' => $status_last,
      'status_teks' => rtrim($status_teks,', '),
      'cdate' => 'NOW()',
      'edate' => $edate,
      'referrer' => $referrer,
      'is_failed' => $is_failed,
      'is_process' => $is_process,
      'is_active' => '1'
    );
    $res = $this->cam->set($di);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
      if(empty($is_failed) && empty($d['sess']->user->is_confirmed)) {
        // $this->_sendEmailVerificationCode($d['sess']->user);
      }
      $this->bum->update($d['sess']->user->id,array('apply_statno'=>1));

      $this->_updateProgress($d['sess']->user->id, $res, 'apply');
    }else{
      $this->status = 970;
      $this->message = 'Lamaran gagal diproses';
    }

    $this->__json_out($data);
  }
  public function analytics(){
    $d = $this->__init();
    $p = array();
    $bu = strlen(base_url());
    $referrer = $this->input->post('referrer','');
    if(strlen($referrer) > $bu){
      $p = parse_url(substr($referrer, $bu));
    }
    if(isset($p['path'])){
      $p = pathinfo($p['path']);
    }
    if(isset($p['dirname'],$p['filename']) && $p['dirname'] == 'jobs/detail'){
      $p['filename'] = (int) $p['filename'];
      if($p['filename']>0){
        $this->status = 200;
        $referrer = '';
        if(isset($d['sess']->jobs->referrer) && strlen($d['sess']->jobs->referrer)){
          $referrer = $d['sess']->jobs->referrer;
        }
        $this->blvm->updateViewCount($p['filename'], $referrer);
      }
    }
    $this->__json_out($p);
  }
}
