<?php
class TestSkill extends JI_Controller{

	public function __construct(){
		parent::__construct();
		$this->lib("seme_purifier");
		$this->load("api_admin/a_banksoal_model",'absm');
		$this->load("api_admin/b_soal_model",'bsm');
		$this->current_parent = 'ujian_testskill';
		$this->current_page = 'ujian_testskill';
	}

	private function __slugify($text){
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		// trim
		$text = trim($text, '-');
		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);
		// lowercase
		$text = strtolower($text);
		if (empty($text)) {
			return 'n-a';
		}
		return $text;
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
		$tbl_as = $this->absm->tbl_as;

		//sorting logic
		switch($iSortCol_0){
			case 0:
				$sortCol = "$tbl_as.id";
				break;
			case 1:
				$sortCol = "$tbl_as.utype";
				break;
			case 2:
				$sortCol = "$tbl_as.nama";
				break;
			case 3:
				$sortCol = "$tbl_as.waktu";
				break;
			default:
			$sortCol = "$tbl_as.id";
		}

		if(empty($draw)) $draw = 0;
		if(empty($pagesize)) $pagesize=10;
		if(empty($page)) $page=0;

		$keyword = $sSearch;

		//advanced search / filter
		$utype = $this->input->post("utype",'');
		$is_active = $this->input->post("is_active",'');
		$is_mandatory=0;
		$is_test_awal=1;

		$this->status = 200;
		$this->message = 'Berhasil';
		$dcount = $this->absm->countAll($keyword,$utype,$is_active,$is_mandatory,$is_test_awal);
		$ddata = $this->absm->getAll($page,$pagesize,$sortCol,$sortDir,$keyword,$utype,$is_active,$is_mandatory,$is_test_awal);



    $ids = array();
    $soal_count = array();
    foreach ($ddata as $gd) {
      $ids[] = $gd->id;
			$soal_count[$gd->id] = 0;
    }
    if(count($ids)){
      $bsm = $this->bsm->countByBankSoalIds($ids);
      foreach($bsm as $bs){
        if(isset($soal_count[$bs->a_banksoal_id])){
          $soal_count[$bs->a_banksoal_id] = $bs->total;
        }
      }
      unset($bsm,$bs,$ids);
    }

		foreach($ddata as &$gd){
			if(isset($gd->nama)){
				$gd->nama = htmlentities(rtrim($gd->nama,' - '));
			}
			if(isset($gd->utype)){
				$gd->utype = strtolower($gd->utype);
				switch($gd->utype){
					case 'pilihan':
						$gd->utype = 'Pilihan Ganda';
						break;
					case 'essay':
						$gd->utype = 'Essay';
						break;
					default:
						$gd->utype = '-';
				}

			}
			if(isset($gd->soal_count) && $soal_count[$gd->id]){
				$gd->soal_count = $soal_count[$gd->id].' soal';
			}
			if(isset($gd->is_active)){
				if(!empty($gd->is_active)){
					$gd->is_active = '<label class="label label-success">Aktif</label>';
				}else{
					$gd->is_active = '<label class="label label-default">Tidak Aktif</label>';
				}
				if(isset($gd->is_vendor)){
					if(!empty($gd->is_vendor)){
						$gd->is_active .= ' <label class="label label-warning">Vendor</label>';
					}else{
						$gd->is_active .= ' <label class="label label-default">Internal</label>';
					}

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
		$dcount = $this->absm->countAll($page,$pagesize,$keyword);
		$ddata = $this->absm->getAll($page,$pagesize,$keyword);

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

		$data = array();
		$data['id'] = '0';
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$di = $_POST;
		if(!isset($di['nama'])) $di['nama'] = "";
		if(strlen($di['nama'])<=0){
			$this->status = 101;
			$this->message = 'Diperlukan satu atau lebih paramater';
			$this->__json_out($data);
			die();
		}

		//image validation

		//start transaction
		$this->absm->trans_start();
		//get last id
		$acm_id = $this->absm->getLastId();

		//build primary key
		$di['id'] = $acm_id;
		//insert into db
		$res = $this->absm->set($di);
		if($res){
			$this->absm->trans_commit();
			$this->status = 200;
			$this->message = 'Data baru berhasil ditambahkan';
			$data['id'] = ''.$acm_id;
		}else{
			$this->status = 110;
			$this->message = 'Gagal menyisipkan cabang ke basis data';
			$this->absm->trans_rollback();
		}
		$this->absm->trans_end();
		$this->__json_out($data);
	}
	public function detail($id){
		$id = (int) $id;
		$d = $this->__init();
		$data = array();
		if(!$this->admin_login && empty($id)){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$this->status = 200;
		$this->message = 'Berhasil';
		$data = $this->absm->getById($id);
		if(!isset($data->id)){
			$data = new stdClass();
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}
		$this->__json_out($data);
	}
	public function edit($id){
		$d = $this->__init();
		$data = array();

		$id = (int) $id;
		if($id<=0){
			$this->status = 444;
			$this->message = 'Invalid Cabang ID';
			$this->__json_out($data);
			die();
		}

		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;


		$du = $_POST;
		if(isset($du['id'])) unset($du['id']);
		if(!isset($du['nama'])) $du['nama'] = "";
		if(strlen($du['nama'])<=0){
			$this->status = 110;
			$this->message = 'Nama wajib diisi';
			$this->__json_out($data);
			die();
		}

		if(!isset($du['ket'])) $du['ket'] = "";
		$res = $this->absm->update($id, $du);
		if($res){
			$this->status = 200;
			$this->message = 'Success';
		}else{
			$this->status = 901;
			$this->message = 'Tidak dapat menambah cabang';
		}
		$this->__json_out($data);
	}
	public function hapus($id){
		$id = (int) $id;
		$d = $this->__init();
		$data = array();
		if($id<=0){
			$this->status = 500;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}
		if(!$this->admin_login && empty($id)){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$acm = $this->absm->getById($id);
		if(!isset($absm->id)){
			$this->status = 520;
			$this->message = 'ID not found or has been deleted';
			$this->__json_out($data);
			die();
		}
		$res = $this->absm->del($id);
		if($res){
			$this->status = 200;
			$this->message = 'Berhasil';
		}else{
			$this->status = 902;
			$this->message = 'Tidak dapat menghapus cabang';
		}
		$this->__json_out($data);
	}
	public function get(){
		$keyword = $this->input->request("keyword");
		if(empty($keyword)) $keyword="";
		$p = new stdClass();
		$p->id = 'NULL';
		$p->text = '-';
		$data = $this->absm->getSearch($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}
	public function get_parent(){
		$keyword = $this->input->request("keyword");
		if(empty($keyword)) $keyword="";
		$p = new stdClass();
		$p->id = '';
		$p->text = '--Semua--';
		$data = $this->absm->getParentSearch($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}
	public function vendor_cari(){
		$keyword = $this->input->request("keyword");
		if(empty($keyword)) $keyword="";
		$p = new stdClass();
		$p->id = 'NULL';
		$p->text = '-';
		$data = $this->absm->getSearchVendor($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}

}
