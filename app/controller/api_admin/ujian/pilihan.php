<?php
class Pilihan extends JI_Controller{

	public function __construct(){
		parent::__construct();
		$this->lib("seme_purifier");
		$this->load("api_admin/a_banksoal_model",'absm');
		$this->load("api_admin/b_soal_model",'bsm');
		$this->load("api_admin/b_soal_pilihan_model",'bspm');
	}

	public function index($b_soal_id=""){
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			return ;
		}
    $b_soal_id = (int) $b_soal_id;
    if($b_soal_id<=0){
			$this->status = 404;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			return ;
    }
    $this->status = 200;
    $this->message = 'Berhasil';
    $data = $this->bspm->getBySoalId($b_soal_id);
    $this->__json_out($data);
	}

	public function baru(){
		$d = $this->__init();

		$data = new stdClass();
		$data->id = '';
		$data->b_soal_id = '';
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			return ;
		}
		$pengguna = $d['sess']->admin;

		$di = $_POST;
		$di['utype'] = 'teks';

		if(!isset($di['b_soal_id'])) $di['b_soal_id'] = 0;
		$di['b_soal_id'] = (int) $di['b_soal_id'];
		if($di['b_soal_id'] <= 0){
			$this->status = 119;
			$this->message = 'ID Soal tidak valid';
			$this->__json_out($data);
			return ;
		}

    $bsm = $this->bsm->getById($di['b_soal_id']);
		if(!isset($bsm->id)){
			$this->status = 118;
			$this->message = 'Data Soal dengan ID tersebut tidak ditemukan';
			$this->__json_out($data);
			return ;
		}

		if(!isset($di['pilihan'])) $di['pilihan'] = '';
		if(!isset($di['bobot_m'])) $di['bobot_m'] = 'NULL';
		if(!isset($di['bobot_l'])) $di['bobot_l'] = 'NULL';
		if(!isset($di['bobot'])) $di['bobot'] = 'NULL';

		//start transaction
		$this->bsm->trans_start();
		//get last id
		$bspm_id = $this->bspm->getLastId();

		//build primary key
		$di['id'] = $bspm_id;
		//insert into db
		$res = $this->bspm->set($di);
		if($res){
			$data->id = (int) $bspm_id;
			$data->b_soal_id = (int) $bsm->id;
			$this->bsm->trans_commit();
			$this->status = 200;
			$this->message = 'Berhasil';
		}else{
			$this->status = 909;
			$this->message = 'Tidak dapat menyimpan data ke database';
			$this->bsm->trans_rollback();
		}
		$this->bsm->trans_end();
		$this->__json_out($data);
	}

	public function detail($id){
		$d = $this->__init();
		$data = new stdClass();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			return ;
		}
		$id = (int) $id;
		if($id<=0){
			$this->status = 470;
			$this->message = 'Invalid Soal ID';
			$this->__json_out($data);
			return ;
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$data = $this->bspm->getById($id);
		if(!isset($data->id)){
			$data = new stdClass();
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			return ;
		}
		$this->__json_out($data);
	}
	public function edit(){
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			return ;
		}

		$id = (int) $this->input->post('id');
		if($id<=0){
			$this->status = 444;
			$this->message = 'Invalid Soal ID';
			$this->__json_out($data);
			return ;
		}
		$bspm = $this->bspm->getById($id);
		if(!isset($bspm->id)){
			$this->status = 814;
			$this->message = 'Pilihan dengan ID tersebut tidak dapat ditemukan';
			$this->__json_out($data);
			return ;
		}

		$du = $_POST;
		if(isset($du['id'])) unset($du['id']);
		if(!isset($du['pilihan'])) $du['pilihan'] = '';
		if(!isset($du['bobot_m'])) $du['bobot_m'] = 'NULL';
		if(!isset($du['bobot_l'])) $du['bobot_l'] = 'NULL';

		$res = $this->bspm->update($id, $du);
		if($res){
			$this->status = 200;
			$this->message = 'Success';
		}else{
			$this->status = 901;
			$this->message = 'Perubahan ke database tidak dapat dilakukan';
		}
		$this->__json_out($data);
	}
	public function hapus(){
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			return ;
		}

		$b_soal_id = (int) $this->input->post('b_soal_id');
		if($b_soal_id<=0){
			$this->status = 414;
			$this->message = 'Invalid Soal ID';
			$this->__json_out($data);
			return ;
		}

		$id = (int) $this->input->post('id');
		if($id<=0){
			$this->status = 414;
			$this->message = 'Invalid Soal Pilihan ID';
			$this->__json_out($data);
			return ;
		}

		$bspm = $this->bspm->getById($id);
		if(!isset($bspm->id)){
			$this->status = 512;
			$this->message = 'ID not found or has been deleted';
			$this->__json_out($data);
			return ;
		}

		$res = $this->bspm->delByIdSoalId($id,$b_soal_id);
		if($res){
			$this->status = 200;
			$this->message = 'Berhasil';
		}else{
			$this->status = 902;
			$this->message = 'Gagal menghapus data dari database';
		}
		$this->__json_out($data);
	}
	public function swap(){
		$id1 = (int) $this->input->post('id1');
		$id2 = (int) $this->input->post('id2');
		$a = $this->bspm->getById($id1);
		$b = $this->bspm->getById($id2);
		if(isset($a->id) && isset($b->id)){
			$this->bspm->update($id1, ['urutan' => (int) $this->input->post('ur1')]);
			$this->bspm->update($id2, ['urutan' => (int) $this->input->post('ur2')]);
			$this->status = 200;
			$this->message = 'Berhasil';
		}else{
			$this->status = 902;
			$this->message = 'Gagal menghapus data dari database';
		}
		$this->__json_out($data);
	}
}
