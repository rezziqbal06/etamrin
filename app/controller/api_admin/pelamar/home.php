<?php
class Home extends JI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->lib("seme_purifier");
		$this->load("api_admin/a_banksoal_model", 'absm');
		$this->current_parent = 'ujian_banksoal';
		$this->current_page = 'ujian_banksoal';
		$this->load('api_admin/b_user_model', 'bum');
		$this->load('api_admin/d_offer_model', 'dom');
	}

	private function __slugify($text)
	{
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

	public function index()
	{
		$d = $this->__init();
		$data = array();
		if (!$this->admin_login) {
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$draw = $this->input->post("draw");
		$sval = $this->input->post("search");
		$sSearch = $this->input->post("sSearch", '');
		$sEcho = $this->input->post("sEcho");
		$page = $this->input->post("iDisplayStart");
		$pagesize = $this->input->post("iDisplayLength");

		$iSortCol_0 = $this->input->post("iSortCol_0");
		$sSortDir_0 = $this->input->post("sSortDir_0");


		$sortDir = strtoupper($sSortDir_0);
		if (empty($sortDir)) $sortDir = "DESC";
		if (strtolower($sortDir) != "desc") {
			$sortDir = "ASC";
		}

		//get table alias
		$tbl_as = $this->bum->tbl_as;
		$tbl2_as = $this->bum->tbl2_as;
		$tbl3_as = $this->bum->tbl3_as;

		//sorting logic
		switch ($iSortCol_0) {
			case 0:
				$sortCol = "$tbl_as.fnama";
				break;
			case 1:
				$sortCol = "COALESCE($tbl3_as.nama,'-')";
				break;
			case 2:
				$sortCol = "CONCAT(REPLACE(REPLACE($tbl_as.kabkota,'Kabupaten ',''), 'Kota ','') ,', ',$tbl_as.provinsi)";
				break;
			case 3:
				$sortCol = "$tbl_as.telp";
				break;
			case 4:
				$sortCol = "$tbl_as.umur";
				break;
			case 5:
				$sortCol = "$tbl_as.email";
				break;
			case 6:
				$sortCol = "$tbl_as.cdate";
				break;
			case 7:
				$sortCol = "$tbl_as.is_active";
				break;
			default:
				$sortCol = "$tbl_as.id";
		}

		if (empty($draw)) $draw = 0;
		if (empty($pagesize)) $pagesize = 10;
		if (empty($page)) $page = 0;

		$keyword = $sSearch;

		//advanced search / filter
		$b_lowongan_id = $this->input->post("b_lowongan_id", '');
		if (strlen($b_lowongan_id)) {
			$b_lowongan_id = (int) $b_lowongan_id;
			if ($b_lowongan_id <= 0) $b_lowongan_id = '';
		}
		$sdate = $this->input->post("sdate", '');
		if (strlen($sdate) != 10) {
			$sdate = '';
		}
		$edate = $this->input->post("edate", '');
		if (strlen($edate) != 10) {
			$edate = '';
		}
		$utype = $this->input->post("utype", '');
		$is_active = $this->input->post("is_active", '');
		$filters = $this->input->post("filters", '');
		if (strlen($filters) <= 2) $filters = '{}';
		$filters = json_decode($filters);
		if (!is_object($filters)) $filters = new stdClass();

		$this->status = 200;
		$this->message = 'Berhasil';
		$dcount = $this->bum->countPelamarAll($keyword, $filters, $b_lowongan_id, $sdate, $edate);
		$ddata = $this->bum->getPelamarAll($page, $pagesize, $sortCol, $sortDir, $keyword, $filters, $b_lowongan_id, $sdate, $edate);

		foreach ($ddata as &$gd) {
			if (isset($gd->fnama)) {
				$nama = '<div class="row">';
				$nama .= '<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input id="cb_' . $gd->b_user_id . '" type="checkbox" class="form-control input-checkboxes" data-id="' . $gd->b_user_id . '" /></div>';
				$nama .= '<div class="col-xs-10 col-sm-3 col-md-3 col-lg-2 col-xl-2"><a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="btn-option-show" data-id="' . $gd->id . '"><img src="' . (isset($gd->foto) ? $this->cdn_url($gd->foto) : '') . '" class="user-foto-circle" onerror="this.error=null;this.src=\'' . $this->cdn_url('media/pengguna/default.png') . '\'" /></a></div>';
				$nama .= '<div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 col-xl-8"> <a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="btn-option-show user-nama" data-id="' . $gd->id . '">' . ucwords(strtolower($gd->fnama)) . '</a></div>';
				$nama .= '</div>';
				$gd->fnama = $nama;
				unset($nama);
			}
			if (isset($gd->umur)) {
				$gd->umur = '<span>' . $gd->umur . ' Tahun</span>';
			}
			if (isset($gd->is_active)) {
				if ($gd->is_active) {
					$gd->is_active = '<a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="text-success btn-option-showx" data-id="' . $gd->id . '" title="User aktif"><i class="fa fa-circle fa-2x"></i></a> ';
				} else {
					$gd->is_active = '<a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="text-grey btn-option-showx" data-id="' . $gd->id . '"  title="User Tidak aktif"><i class="fa fa-circle fa-2x"></i></a> ';
				}
				$gd->is_active .= '&nbsp;&nbsp;';
				if (isset($gd->jk)) {
					if ($gd->jk) {
						$gd->is_active .= '<a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="text-info btn-option-showx" data-id="' . $gd->id . '"  title="Laki-laki"><i class="fa fa-mars fa-2x"></i></a> ';
					} else {
						$gd->is_active .= '<a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="text-danger btn-option-showx" data-id="' . $gd->id . '"  title="Perempuan"><i class="fa fa-venus fa-2x"></i></a> ';
					}
				}
				$gd->is_active .= '&nbsp;&nbsp;';
				if (isset($gd->is_confirmed)) {
					if ($gd->is_confirmed) {
						$gd->is_active .= '<a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="text-success btn-option-showx" data-id="' . $gd->id . '"  title="Email terkonfirmasi"><i class="fa fa-envelope fa-2x"></i></a> ';
					} else {
						$gd->is_active .= '<a href="' . base_url_admin('pelamar/home/detail/' . $gd->id) . '" class="text-danger btn-option-showx" data-id="' . $gd->id . '"  title="Belum konfirmasi Email"><i class="fa fa-envelope fa-2x"></i></a> ';
					}
				}
				$gd->is_active .= '&nbsp;&nbsp;';
				if (isset($gd->apply_statno)) {
					switch ($gd->apply_statno) {
						case '0':
							$gd->is_active .= '<a href="#" class="text-warning" data-id="' . $gd->id . '"  title="Belum Melamar"><i class="fa fa-hourglass fa-2x"></i></a> ';
							break;
						case '1':
							$gd->is_active .= '<a href="#" class="text-info" data-id="' . $gd->id . '"  title="Dalam Proses"><i class="fa fa-cog fa-spin fa-2x"></i></a> ';
							break;
						case '2':
							$gd->is_active .= '<a href="#" class="text-info" data-id="' . $gd->id . '"  title="Seleksi Administrasi"><i class="fa fa-file-text-o fa-2x"></i></a> ';
							break;
						case '3':
							$gd->is_active .= '<a href="#" class="text-primary" data-id="' . $gd->id . '"  title="Tahap Tes"><i class="fa fa-file-excel-o fa-2x"></i></a> ';
							break;
						case '4':
							$gd->is_active .= '<a href="#" class="text-warning" data-id="' . $gd->id . '"  title="Tahap Interview"><i class="fa fa-volume-control-phone fa-2x"></i></a> ';
							break;
						case '5':
							$gd->is_active .= '<a href="#" class="text-info" data-id="' . $gd->id . '"  title="Negosiasi Kontrak"><i class="fa fa-inbox fa-2x"></i></a> ';
							break;
						case '8':
							$gd->is_active .= '<a href="#" class="text-danger" data-id="' . $gd->id . '"  title="Gagal"><i class="fa fa-times fa-2x"></i></a> ';
							break;
						case '9':
							$gd->is_active .= '<a href="#" class="text-success" data-id="' . $gd->id . '"  title="Selesai"><i class="fa fa-check fa-2x"></i></a> ';
							break;
						default:
							$gd->is_active .= '<a href="#" class="text-grey" data-id="' . $gd->id . '"  title="Belum Melamar"><i class="fa fa-info-circle fa-2x"></i><i class="fa fa-check"></i></a> ';
					}
				}
				$gd->is_active .= '&nbsp;&nbsp;';
			}
		}

		$this->__jsonDataTable($ddata, $dcount);
	}
	public function get_data()
	{
		$d = $this->__init();
		$data = array();
		if (!$this->admin_login) {
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
		if (empty($sortDir)) $sortDir = "DESC";
		if (strtolower($sortDir) != "desc") {
			$sortDir = "ASC";
		}



		if (empty($draw)) $draw = 0;
		if (empty($pagesize)) $pagesize = 10;
		if (empty($page)) $page = 0;

		$keyword = $sSearch;

		$this->status = 200;
		$this->message = 'Berhasil';
		$dcount = $this->absm->countAll($page, $pagesize, $keyword);
		$ddata = $this->absm->getAll($page, $pagesize, $keyword);

		foreach ($ddata as &$gd) {



			if (isset($gd->is_active)) {
				if (!empty($gd->is_active)) {
					$gd->is_active = 'Aktif';
				} else {
					$gd->is_active = 'Tidak Aktif';
				}
			}
		}

		$data['cabang'] = $ddata;
		//sleep(3);
		$another = array();
		$this->__json_out($ddata);
	}
	public function baru()
	{
		$d = $this->__init();

		$data = array();
		$data['id'] = '0';
		if (!$this->admin_login) {
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$di = $_POST;

		if (!isset($di['nama'])) $di['nama'] = "";
		if (strlen($di['nama']) <= 0) {
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
		if ($res) {
			$this->absm->trans_commit();
			$this->status = 200;
			$this->message = 'Data baru berhasil ditambahkan';
			$data['id'] = '' . $acm_id;
		} else {
			$this->status = 110;
			$this->message = 'Gagal menyisipkan cabang ke basis data';
			$this->absm->trans_rollback();
		}
		$this->absm->trans_end();
		$this->__json_out($data);
	}
	public function detail($id)
	{
		$id = (int) $id;
		$d = $this->__init();
		$data = array();
		if (!$this->admin_login && empty($id)) {
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
		if (!isset($data->id)) {
			$data = new stdClass();
			$this->status = 441;
			$this->message = 'No Data';
			$this->__json_out($data);
			die();
		}
		$this->__json_out($data);
	}
	public function edit($id)
	{
		$d = $this->__init();
		$data = array();

		$id = (int) $id;
		if ($id <= 0) {
			$this->status = 444;
			$this->message = 'Invalid Cabang ID';
			$this->__json_out($data);
			die();
		}

		if (!$this->admin_login) {
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;


		$du = $_POST;

		if (isset($du['id'])) unset($du['id']);
		if (!isset($du['nama'])) $du['nama'] = "";
		if (strlen($du['nama']) <= 0) {
			$this->status = 110;
			$this->message = 'Nama wajib diisi';
			$this->__json_out($data);
			die();
		}
		if (!isset($du['ket'])) $du['ket'] = "";

		$res = $this->absm->update($id, $du);
		if ($res) {
			$this->status = 200;
			$this->message = 'Success';
		} else {
			$this->status = 901;
			$this->message = 'Tidak dapat menambah cabang';
		}
		$this->__json_out($data);
	}
	public function hapus($id)
	{
		$id = (int) $id;
		$d = $this->__init();
		$data = array();
		if ($id <= 0) {
			$this->status = 500;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}
		if (!$this->admin_login && empty($id)) {
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;

		$acm = $this->absm->getById($id);
		if (!isset($absm->id)) {
			$this->status = 520;
			$this->message = 'ID not found or has been deleted';
			$this->__json_out($data);
			die();
		}
		$res = $this->absm->del($id);
		if ($res) {
			$this->status = 200;
			$this->message = 'Berhasil';
		} else {
			$this->status = 902;
			$this->message = 'Tidak dapat menghapus cabang';
		}
		$this->__json_out($data);
	}
	public function get()
	{
		$keyword = $this->input->request("keyword");
		if (empty($keyword)) $keyword = "";
		$p = new stdClass();
		$p->id = 'NULL';
		$p->text = '-';
		$data = $this->absm->getSearch($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}
	public function get_parent()
	{
		$keyword = $this->input->request("keyword");
		if (empty($keyword)) $keyword = "";
		$p = new stdClass();
		$p->id = '';
		$p->text = '--Semua--';
		$data = $this->absm->getParentSearch($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}
	public function vendor_cari()
	{
		$keyword = $this->input->request("keyword");
		if (empty($keyword)) $keyword = "";
		$p = new stdClass();
		$p->id = 'NULL';
		$p->text = '-';
		$data = $this->absm->getSearchVendor($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}

	public function offering()
	{
		$d = $this->__init();
		$data = array();

		$c_apply_id = (int) $_POST['c_apply_id'];
		if ($c_apply_id <= 0) {
			$this->status = 500;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}

		if (!$this->admin_login) {
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;
		$di = $_POST;

		if (is_array($di['bum'])) {
			$bum_hasil['Nama']	= $di['bum']['fnama'];
			$bum_hasil['TTL']	= $di['bum']['tlahir'] . " / " . $this->__dateIndonesia($di['bum']['bdate']);
			$bum_hasil['No. KTP/SIM']	= $di['bum']['noktp'] . "/" . $di['bum']['noktp'];
			$bum_hasil['Status Pajak']	= $di['bum']['pajak_status'];
			$bum_hasil['Alamat'] = $di['bum']['alamat'] . ", " . $di['bum']['alamat2'] . ", " . $di['bum']['desakel'] . ", " . $di['bum']['kecamatan'] . ", " . $di['bum']['kabkota'] . ", " . $di['bum']['provinsi'];
			$bum_hasil['Domisili'] = $di['bum']['alamat'] . ", " . $di['bum']['alamat2'] . ", " . $di['bum']['desakel'] . ", " . $di['bum']['kecamatan'] . ", " . $di['bum']['kabkota'] . ", " . $di['bum']['provinsi'];
			$bum_hasil['Jabatan']	= $di['bum']['jabatan'];
			$bum_hasil['Departemen']	= $di['bum']['departemen'];
			$bum_hasil['Level']	= $di['bum']['level'];
			$bum_hasil['Status Kontrak']	= $di['bum']['status_kontrak'];
			$bum_hasil['Tgl. Masuk']	= $di['bum']['tgl_masuk'];
			$bum_hasil['Lokasi Kerja']	= $di['bum']['lokasi_kerja'];

			if (isset($bum_hasil)) $di['bum_hasil'] = json_encode($bum_hasil);
			unset($di['bum']);
		}

		if (is_array($di['offer'])) {
			foreach ($di['offer'] as $k => $v) {
				$offering_hasil[$k] = $v;
			}
			if (isset($offering_hasil)) $di['offering_hasil'] = json_encode($offering_hasil);
			unset($di['offer']);
		}

		$res = $this->dom->set($di);
		if ($res) {
			$this->status = 200;
			$this->message = 'Offering akan muncul di halaman kandidat.';
		} else {
			$this->status = 902;
			$this->message = 'Tidak dapat menambah offering';
		}
		$this->__json_out($data);
	}

	public function edit_offering()
	{
		$d = $this->__init();
		$data = array();

		$id = (int) $_POST['id'];
		if ($id <= 0) {
			$this->status = 500;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}

		unset($_POST['id']);

		if (!$this->admin_login) {
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			header("HTTP/1.0 400 Harus login");
			$this->__json_out($data);
			die();
		}

		$dom = $this->dom->getById($id);
		if (!isset($dom->id)) {
			$this->status = 401;
			$this->message = 'Offering not found or deleted';
			$this->__json_out($data);
			die();
		}
		$pengguna = $d['sess']->admin;
		$di = $_POST;

		if (is_array($di['bum'])) {
			$bum_hasil['Nama']	= $di['bum']['fnama'];
			$bum_hasil['TTL']	= $di['bum']['tlahir'] . " / " . $this->__dateIndonesia($di['bum']['bdate']);
			$bum_hasil['No. KTP/SIM']	= $di['bum']['noktp'] . "/" . $di['bum']['noktp'];
			$bum_hasil['Status Pajak']	= $di['bum']['pajak_status'];
			$bum_hasil['Alamat'] = $di['bum']['alamat'] . ", " . $di['bum']['alamat2'] . ", " . $di['bum']['desakel'] . ", " . $di['bum']['kecamatan'] . ", " . $di['bum']['kabkota'] . ", " . $di['bum']['provinsi'];
			$bum_hasil['Domisili'] = $di['bum']['alamat'] . ", " . $di['bum']['alamat2'] . ", " . $di['bum']['desakel'] . ", " . $di['bum']['kecamatan'] . ", " . $di['bum']['kabkota'] . ", " . $di['bum']['provinsi'];
			$bum_hasil['Jabatan']	= $di['bum']['jabatan'];
			$bum_hasil['Departemen']	= $di['bum']['departemen'];
			$bum_hasil['Level']	= $di['bum']['level'];
			$bum_hasil['Status Kontrak']	= $di['bum']['status_kontrak'];
			$bum_hasil['Tgl. Masuk']	= $di['bum']['tgl_masuk'];
			$bum_hasil['Lokasi Kerja']	= $di['bum']['lokasi_kerja'];
			if (isset($bum_hasil)) $di['bum_hasil'] = json_encode($bum_hasil);
			unset($di['bum']);
		}

		if (is_array($di['offer'])) {
			foreach ($di['offer'] as $k => $v) {
				$offering_hasil[$k] = $v;
			}
			if (isset($offering_hasil)) $di['offering_hasil'] = json_encode($offering_hasil);
			unset($di['offer']);
		}

		$res = $this->dom->update($id, $di);
		if ($res) {
			$this->status = 200;
			$this->message = 'Data Berhasil Diubah. Offering akan muncul di halaman kandidat.';
		} else {
			$this->status = 902;
			$this->message = 'Tidak dapat menambah offering';
		}
		$this->__json_out($data);
	}
}
