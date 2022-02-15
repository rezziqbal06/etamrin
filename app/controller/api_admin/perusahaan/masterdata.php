<?php
class MasterData extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->lib("seme_purifier");
		$this->load("api_admin/a_company_model",'acm');
		$this->current_parent = 'perusahaan';
		$this->current_page = 'perusahaan_masterdata';
	}

	private function __imageValidation($imgkey){
		$data = array();
		//image validation
		if(!isset($_FILES[$imgkey])){
			$this->status = 102;
			$this->message = 'Diperlukan file ikon gambar';
			$this->__json_out($data);
			die();
		}
		if(empty($_FILES[$imgkey]['tmp_name'])){
			$this->status = 103;
			$this->message = 'Gagal mengunggah gambar ikon';
			$this->__json_out($data);
			die();
		}
		if($_FILES[$imgkey]['size']<=0){
			$this->status = 104;
			$this->message = 'Gagal mengunggah gambar ikon';
			$this->__json_out($data);
			die();
		}
		if($_FILES[$imgkey]['size']>100000){
			$this->status = 105;
			$this->message = 'Ukuran file ikon gambar terlalu besar, silakan coba gambar lain';
			$this->__json_out($data);
			die();
		}
		if(mime_content_type($_FILES[$imgkey]['tmp_name']) == "image/webp"){
			$this->status = 106;
			$this->message = 'Format file WebP saat ini tidak didukung oleh sistem ini, silakan coba gambar lain';
			$this->__json_out($data);
			die();
		}
		if(mime_content_type($_FILES[$imgkey]['tmp_name']) == "image/webp"){
			$this->status = 106;
			$this->message = 'Format file WebP saat ini tidak didukung oleh sistem ini, silakan coba gambar lain';
			$this->__json_out($data);
			die();
		}
		$ext = strtolower(pathinfo($_FILES[$imgkey]['name'], PATHINFO_EXTENSION));
		if (!in_array($ext, array("gif", "jpg", "png","jpeg"))) {
			$this->status = 107;
			$this->message = 'Ekstensi file tidak valid, hanya mendukung ekstensi PNG atau JPG';
			$this->__json_out($data);
			die();
		}
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
		$tbl_as = $this->acm->getTblAs();

		//sorting logic
		switch($iSortCol_0){
			case 0:
				$sortCol = "$tbl_as.id";
				break;
			case 1:
				$sortCol = "$tbl_as.utype";
				break;
			case 2:
				$sortCol = "$tbl_as.kode";
				break;
			case 3:
				$sortCol = "$tbl_as.nama";
				break;
			case 4:
				$sortCol = "$tbl_as.alamat";
				break;
			case 5:
				$sortCol = "$tbl_as.telp";
				break;
			case 6:
				$sortCol = "$tbl_as.is_active";
				break;
			default:
				$sortCol = "$tbl_as.id";
		}

		if(empty($draw)) $draw = 0;
		if(empty($pagesize)) $pagesize=10;
		if(empty($page)) $page=0;

		$keyword = $sSearch;

		//advanced search / filter
		$utype = $this->input->post("utype");
		if(empty($utype)) $utype = "";
		$badan_hukum = $this->input->post("badan_hukum");
		if(empty($badan_hukum)) $badan_hukum = "";
		$is_vendor = $this->input->post("is_vendor");
		$is_active = $this->input->post("is_active");

		$this->status = 200;
		$this->message = 'Berhasil';

		$dcount = $this->acm->countAll($keyword,$utype,$is_vendor,$badan_hukum,$is_active);
		$ddata = $this->acm->getAll($page,$pagesize,$sortCol,$sortDir,$keyword,$utype,$is_vendor,$badan_hukum,$is_active);
		foreach($ddata as &$gd){
			if(isset($gd->nama)){
				$gd->nama = htmlentities(ltrim($gd->nama,' - '));
			}
			if(isset($gd->alamat)){
				$gd->alamat = htmlentities(ltrim($gd->alamat,', '));
			}
			if(isset($gd->utype)){
				$gd->utype = ucfirst($gd->utype);
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
		$dcount = $this->acm->countAll($page,$pagesize,$keyword);
		$ddata = $this->acm->getAll($page,$pagesize,$keyword);

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
		$this->acm->trans_start();
		//get last id
		$acm_id = $this->acm->getLastId();

		//build primary key
		$di['id'] = $acm_id;
		//insert into db
		$res = $this->acm->set($di);
		if($res){
			$this->acm->trans_commit();
			$this->status = 200;
			$this->message = 'Data baru berhasil ditambahkan';
		}else{
			$this->status = 110;
			$this->message = 'Gagal menyisipkan cabang ke basis data';
			$this->acm->trans_rollback();
		}
		$this->acm->trans_end();
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
		$data = $this->acm->getById($id);
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
			$this->message = 'Nama harus DOMProcessingInstruction';
			$this->__json_out($data);
			die();
		}

		$res = $this->acm->update( $id, $du);
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

		$kategori = $this->acm->getById($id);
		if(!isset($kategori->id)){
			$this->status = 520;
			$this->message = 'ID not found or has been deleted';
			$this->__json_out($data);
			die();
		}
		$res = $this->acm->del($id);
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
		$data = $this->acm->getSearch($keyword);
		array_unshift($data, $p);
    $this->__json_select2($data);
  }
  public function vendor_cari(){
    $keyword = $this->input->request("keyword");
		if(empty($keyword)) $keyword="";
		$p = new stdClass();
		$p->id = 'NULL';
		$p->text = '-';
		$data = $this->acm->getSearchVendor($keyword);
		array_unshift($data, $p);
    $this->__json_select2($data);
  }

}
