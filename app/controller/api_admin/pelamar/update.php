<?php
class Update extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load("api_admin/a_jabatan_model", 'ajm');
    $this->load("api_admin/b_lowongan_model", 'blm');
    $this->load("api_admin/b_lowongan_jabatan_model", 'bljm');
    $this->load("api_admin/b_lowongan_banksoal_model", 'blbsm');
    $this->load("api_admin/b_user_model", 'bum');
    $this->load("api_admin/c_apply_model", 'clm');
    $this->lib("seme_upload", 'su');
  }
  public function index(){
  }
  public function is_edit_disabled($value){
    $data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}

    $mindate = $this->input->request('mindate','');
    if(strlen($mindate)!=10) $mindate = '';

    $maxdate = $this->input->request('maxdate','');
    if(strlen($maxdate)!=10) $maxdate = '';

		$pelamar_ids = $this->input->request('pelamar_ids','');
		if(strlen($pelamar_ids)>1){
			$pelamar_ids = explode(',',$pelamar_ids);
		}else{
			$pelamar_ids = array();
		}

		$keyword = $this->input->request('keyword','');
		if(strlen($keyword)<=2) $keyword = '';

		$filters = $this->input->request('filters','');
		if(strlen($filters)<=2) $filters = '{}';
		$filters = json_decode($filters);
		if(!is_object($filters)) $filters = new stdClass();

		$b_lowongan_id = (int) $this->input->request('b_lowongan_id','0');
		if($b_lowongan_id<=0) $b_lowongan_id = '';

    if($value == false || $value == 'false'){
      $value = '0';
    }else{
      $value = '1';
    }

    $data['count_ok'] = 0;
    $data['count_nok'] = 0;
		$pelamar = $this->bum->reportPelamarAll($pelamar_ids,$keyword,$filters,$b_lowongan_id);
    $data['count'] = count($pelamar);
    if(is_array($pelamar) && $data['count']){
      foreach($pelamar as $p){
        $res = $this->bum->update($p->id,array('is_edit_disabled'=>$value));
        if($res){
          $data['count_ok']++;
        }else{
          $data['count_nok']++;
        }
      }
    }

    $this->__flash($data['count_ok'].' dari '.$data['count'].' data berhasil di update');
    redir(base_url_admin('pelamar/home/'));
  }
  public function lowongan($c_apply_id,$b_lowongan_id){
    $d = $this->__init();
    $data = array();
    if (!$this->admin_login) {
      $this->status = 400;
      $this->message = 'Session telah expired, silakan login lagi';
      header("HTTP/1.0 400 Harus login");
      $this->__json_out($data);
      die();
    }
    $c_apply_id = (int) $c_apply_id;
    if($c_apply_id<=0){
      $this->status = 1400;
      $this->message = 'Invalid apply ID';
      $this->__json_out($data);
      die();
    }
    $b_lowongan_id = (int) $b_lowongan_id;
    if($b_lowongan_id<=0){
      $this->status = 1440;
      $this->message = 'Invalid Lowongan ID';
      $this->__json_out($data);
      die();
    }

    $du = array();
    $du['b_lowongan_id'] = $b_lowongan_id;
    $du['is_active'] = '1';
    $du['is_failed'] = '0';
    $du['is_process'] = '1';
    $clm = $this->clm->getById($c_apply_id);
    if(isset($clm->id)){
      $this->clm->update($clm->id, $du);
    }else{
      $this->status = 1444;
      $this->message = 'Gagal memindahkan lowongan';
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $this->__json_out($data);
  }
}
