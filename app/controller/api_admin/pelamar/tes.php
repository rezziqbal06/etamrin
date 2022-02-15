<?php
class Tes extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->lib("conumtext");
		$this->lib("seme_email");
		$this->lib("seme_purifier");
		$this->load("api_admin/a_jabatan_model",'ajm');
		$this->load("api_admin/a_pengguna_model",'apm');
		$this->load("api_admin/b_user_model",'bum');
		$this->load("api_admin/b_lowongan_model",'blm');
    $this->load("api_admin/c_apply_model",'cam');
		$this->load("api_admin/c_apply_sessiontes_model",'castm');
		$this->load("api_admin/c_interview_model",'cim');
	}
  public function index(){

  }
  public function reset($c_apply_id){
    $c_apply_id = (int) $c_apply_id;
    if($c_apply_id<=0){
      $this->status = 848;
      $this->message = 'Invalid c_apply_id';
    }
    $res = $this->castm->reset($c_apply_id);
    if($res){
      $this->status = 200;
      $this->message = 'Berhasil';
      $cam = $this->cam->getById($c_apply_id);
      if(isset($cam->b_user_id)){
        $this->cam->update($cam->id, array("is_process"=>1, "is_failed"=>0));
        $this->bum->update($cam->b_user_id, array("apply_statno"=>1));
      }
    }else{
      $this->status = 818;
      $this->message = 'Sesi tes tidak dapat diubah saat ini, coba lagi nanti';
    }
    $this->__json_out(new stdClass());
  }
}
