<?php
class Soal extends JI_Controller{

	public function __construct(){
		parent::__construct();
		$this->lib("seme_purifier");
		$this->load("api_admin/a_banksoal_model",'absm');
		$this->load("api_admin/b_soal_model",'bsm');
		$this->load("api_admin/b_soal_pilihan_model",'bspm');
	}

	public function index(){
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$draw = $this->input->post("draw");
		$sval = $this->input->post("search");
		$sSearch = $this->input->post("sSearch");
		$sEcho = $this->input->post("sEcho");
		$page = $this->input->post("iDisplayStart");
		$pagesize = $this->input->post("iDisplayLength");

		$iSortCol_0 = $this->input->post("iSortCol_0");
		$sSortDir_0 = $this->input->post("sSortDir_0");


		$sortDir = strtoupper($sSortDir_0);
		if(empty($sortDir)) $sortDir = "DESC";
		if(strtolower($sortDir) != "desc"){
			$sortDir = "ASC";
		}

		//get table alias
		$tbl_as = $this->bsm->tbl_as;

		//sorting logic
		switch($iSortCol_0){
			case 0:
			$sortCol = "$tbl_as.id";
			break;
			default:
			$sortCol = "$tbl_as.id";
		}

		if(empty($draw)) $draw = 0;
		if(empty($pagesize)) $pagesize=10;
		if(empty($page)) $page=0;

		$keyword = $sSearch;

		//advanced search / filter
		$is_active = $this->input->post("is_active");
		$a_banksoal_id = $this->input->post("a_banksoal_id");
		if(strlen($a_banksoal_id)>0){
			$a_banksoal_id = (int) $a_banksoal_id;
			if($a_banksoal_id<=0) $a_banksoal_id = '';
		}else{
			$a_banksoal_id = '';
		}
		$utype = $this->input->post("utype");
		if(strlen($utype)<=0 || empty($utype)){
			$utype = '';
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$dcount = $this->bsm->countAll($keyword,$a_banksoal_id,$utype,$is_active);
		$ddata = $this->bsm->getAll($page,$pagesize,$sortCol,$sortDir,$keyword,$a_banksoal_id,$utype,$is_active);

		foreach($ddata as &$gd){
			if(isset($gd->soal)){
				$gd->soal = $gd->soal;
			}
			if(isset($gd->utype)){
				$gd->utype = ucfirst($gd->utype);
			}
			if(isset($gd->alamat)){
				$gd->alamat = htmlentities(ltrim($gd->alamat,', '));
			}
			if(isset($gd->is_active)){

				if(!empty($gd->is_active)){
					$gd->is_active = '<label class="label label-success">Aktif</label>';
				}else{
					$gd->is_active = '<label class="label label-default">Tidak Aktif</label>';
				}
			}
		}
		$this->__jsonDataTable($ddata,$dcount);
	}
	public function get_data(){
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$draw = $this->input->post("draw");
		$sval = $this->input->post("search");
		$sSearch = $this->input->post("sSearch");
		$sEcho = $this->input->post("sEcho");
		$page = $this->input->post("iDisplayStart");
		$pagesize = $this->input->post("iDisplayLength");

		$iSortCol_0 = $this->input->post("iSortCol_0");
		$sSortDir_0 = $this->input->post("sSortDir_0");


		$sortCol = "date";
		$sortDir = strtoupper($sSortDir_0);
		if(empty($sortDir)) $sortDir = "DESC";
		if(strtolower($sortDir) != "desc"){
			$sortDir = "ASC";
		}



		if(empty($draw)) $draw = 0;
		if(empty($pagesize)) $pagesize=10;
		if(empty($page)) $page=0;

		$keyword = $sSearch;

		$this->status = 200;
		$this->message = 'Berhasil';
		$dcount = $this->bsm->countAll($page,$pagesize,$keyword);
		$ddata = $this->bsm->getAll($page,$pagesize,$keyword);

		foreach($ddata as &$gd){



			if(isset($gd->is_active)){
				if(!empty($gd->is_active)){
					$gd->is_active = 'Aktif';
				}else{
					$gd->is_active = 'Tidak Aktif';
				}
			}

		}

		$data['cabang'] = $ddata;
		//sleep(3);
		$another = array();
		$this->__json_out($ddata);
	}

	public function baru(){
		$d = $this->__init();

		$data = new stdClass();
		$data->id = '';
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$di = $_POST;
		$di['utype'] = 'text';

		$pilihan = array();
		if(isset($di['pilihan'])) $pilihan = $di['pilihan'];
		unset($di['pilihan']);

		if(!isset($di['a_banksoal_id'])) $di['a_banksoal_id'] = 0;
		$di['a_banksoal_id'] = (int) $di['a_banksoal_id'];
		if($di['a_banksoal_id'] <= 0){
			$this->status = 109;
			$this->message = 'ID Bank soal tidak valid';
			$this->__json_out($data);
			die();
		}

		$absm = $this->absm->getById($di['a_banksoal_id']);
		if(!isset($absm->id)){
			$this->status = 108;
			$this->message = 'Data Bank soal dengan ID tersebut tidak ditemukan';
			$this->__json_out($data);
			die();
		}

		if(!isset($di['soal'])) $di['soal'] = "";

		//start transaction
		$this->bsm->trans_start();
		//get last id
		$bsm_id = $this->bsm->getLastId();

		$di['urutan'] = 0;
		$bsm_last = $this->bsm->lastByBankSoalId($absm->id);
		if(isset($bsm_last->urutan)){
			$di['urutan'] = $bsm_last->urutan+1;
		}

		//build primary key
		$di['id'] = $bsm_id;
		//insert into db
		$res = $this->bsm->set($di);
		if($res){
			$data->id = $bsm_id;
			$this->status = 200;
			$this->message = 'Data baru berhasil ditambahkan';

			$dis = array();
			$i=1;
			foreach($pilihan as $p){
				$di = array();
				$di['b_soal_id'] = $bsm_id;
				$di['pilihan'] = $p;
				$di['urutan'] = $i;
				$di['is_benar'] = 0;
				$dis[] = $di;
				$i++;
			}
			if(is_array($dis) && count($dis)) $this->bspm->setMass($dis);

			$this->bsm->trans_commit();
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
			die();
		}
		$id = (int) $id;
		if($id<=0){
			$this->status = 470;
			$this->message = 'Invalid Soal ID';
			$this->__json_out($data);
			die();
		}

		$pengguna = $d['sess']->admin;

		$this->status = 200;
		$this->message = 'Berhasil';
		$data = $this->bsm->getById($id);
		if(!isset($data->id)){
			$data = new stdClass();
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}
		$data->pilihans = $this->bspm->getBySoalId($id);
		$this->__json_out($data);
	}
	public function edit($id){
		$d = $this->__init();
		$data = array();


		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$id = (int) $id;
		if($id<=0){
			$this->status = 444;
			$this->message = 'Invalid Soal ID';
			$this->__json_out($data);
			die();
		}
		$bsm = $this->bsm->getById($id);
		if(!isset($bsm->id)){
			$this->status = 804;
			$this->message = 'Soal dengan ID tersebut tidak dapat ditemukan';
			$this->__json_out($data);
			die();
		}

		$du = array();
		$du['soal'] = $this->input->post('soal');

		if(isset($du['id'])) unset($du['id']);
		if(!isset($du['soal'])) $du['soal'] = "";

		$res = $this->bsm->update($id, $du);
		if($res){
			$this->status = 200;
			$this->message = 'Success';

			$urutan = 1;
			$pilihans = $this->input->post('pilihans');
			$urutans = $this->input->post('urutans');
			$bobot_l = $this->input->post('bobot_l');
			$bobot_m = $this->input->post('bobot_m');
			$bobot = $this->input->post('bobot');
			$bspms = $this->bspm->getBySoalId($id);
			if(!is_array($pilihans)) $pilihans = array();
			if(count($pilihans) && count($bspms)){
				foreach($bspms as $k=>$bspm){
					if(isset($bspm->id)){
						$du = array();
						$du['pilihan'] = '';
						$du['urutan'] = $urutan;
						$du['bobot_m'] = 'NULL';
						$du['bobot_l'] = 'NULL';
						$du['bobot'] = 'NULL';
						if(isset($pilihans[$k])) $du['pilihan'] = $pilihans[$k];
						if(isset($urutans[$k])) $du['urutan'] = $urutans[$k];
						if(isset($bobot_m[$k])) $du['bobot_m'] = $bobot_m[$k];
						if(isset($bobot_l[$k])) $du['bobot_l'] = $bobot_l[$k];
						if(isset($bobot[$k])) $du['bobot'] = $bobot[$k];
						$this->bspm->update($bspm->id,$du);
					}
					$urutan++;
				}
			}
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
			die();
		}

		$id = (int) $this->input->post('id');
		if($id<=0){
			$this->status = 500;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}

		$bsm = $this->bsm->getById($id);
		if(!isset($bsm->id)){
			$this->status = 501;
			$this->message = 'ID not found or has been deleted';
			$this->__json_out($data);
			die();
		}

		$this->bsm->trans_start();
		$res = $this->bsm->del($id);
		if($res){

			$res = $this->bspm->delBySoalId($id);
			if($res){
				$this->status = 200;
				$this->message = 'Berhasil';
				$this->bsm->trans_commit();
			}else{
				$this->status = 903;
				$this->message = 'Tidak dapat melakukan penghapusan data ke database, penghapusan dibatalkan';
				$this->bsm->trans_rollback();
			}
		}else{
			$this->status = 902;
			$this->message = 'Tidak dapat menghapus data dari database';
			$this->bsm->trans_rollback();
		}
		$this->bsm->trans_end();
		$this->__json_out($data);
	}
	public function get(){
		$keyword = $this->input->request("keyword");
		if(empty($keyword)) $keyword="";
		$p = new stdClass();
		$p->id = 'NULL';
		$p->text = '-';
		$data = $this->bsm->getSearch($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}
	public function get_parent(){
		$keyword = $this->input->request("keyword");
		if(empty($keyword)) $keyword="";
		$p = new stdClass();
		$p->id = '';
		$p->text = '--Semua--';
		$data = $this->bsm->getParentSearch($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}
	public function get_list($a_banksoal_id){
		$this->status = 200;
		$this->message = 'Berhasil';

		$ids = array();
		$data = array();
		foreach($this->bsm->getByBankSoalId($a_banksoal_id) as &$d){
			$ids[] = (int) $d->id;
			$data[$d->id] = $d;
			$data[$d->id]->pilihans = array();
		}

		if(count($data) == 0){
			$this->status = 800;
			$this->message = 'Belum ada soal';
			$this->__json_out($data);
		}

		foreach($this->bspm->getBySoalIds($ids) as $p){
			$id = (int) $p->b_soal_id;
			if(isset($data[$id]->pilihans)){
				$data[$id]->pilihans[] = $p;
			}
		}
		$data = array_values($data);
		$this->__json_out($data);
	}
	public function swap(){
		$data = new stdClass();
		$id1 = (int) $this->input->post('id1');
		$id2 = (int) $this->input->post('id2');
		$a = $this->bsm->getById($id1);
		$b = $this->bsm->getById($id2);
		if(isset($a->urutan) && isset($b->urutan)){
			$this->bsm->update($id1, ['urutan' => (int) $b->urutan]);
			$this->bsm->update($id2, ['urutan' => (int) $a->urutan]);
			$this->status = 200;
			$this->message = 'Berhasil';
		}else{
			$this->status = 902;
			$this->message = 'Gagal swap data dari database';
		}
		$this->__json_out($data);
	}

}
