<?php
class Pengguna extends JI_Controller
{
	var $media_pengguna = 'media/pengguna';

	public function __construct()
	{
		parent::__construct();
		$this->load("api_admin/a_pengguna_model", 'apm');
	}

	private function __uploadFoto($admin_id)
	{
		//building path target
		$fldr = $this->media_pengguna;
		$folder = SEMEROOT . DIRECTORY_SEPARATOR . $fldr . DIRECTORY_SEPARATOR;
		$folder = str_replace('\\', '/', $folder);
		$folder = str_replace('//', '/', $folder);
		$ifol = realpath($folder);

		//check folder
		if (!$ifol) mkdir($folder); //create folder
		$ifol = realpath($folder); //get current realpath

		reset($_FILES);
		$temp = current($_FILES);
		if (is_uploaded_file($temp['tmp_name'])) {
			if (isset($_SERVER['HTTP_ORIGIN'])) {
				// same-origin requests won't set an origin. If the origin is set, it must be valid.
				header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
			}
			header('Access-Control-Allow-Credentials: true');
			header('P3P: CP="There is no P3P policy."');

			// Sanitize input
			if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
				header("HTTP/1.0 500 Invalid file name.");
				return 0;
			}
			// Verify extension
			$ext = strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION));
			if (!in_array($ext, array("gif", "jpg", "png"))) {
				header("HTTP/1.0 500 Invalid extension.");
				return 0;
			}

			// Create magento style media directory
			$temp['name'] = md5($admin_id) . date('is') . '.' . $ext;
			$name  = $temp['name'];
			$name1 = date("Y");
			$name2 = date("m");

			//building directory structure
			if (PHP_OS == "WINNT") {
				if (!is_dir($ifol)) mkdir($ifol);
				$ifol = $ifol . DIRECTORY_SEPARATOR . $name1 . DIRECTORY_SEPARATOR;
				if (!is_dir($ifol)) mkdir($ifol);
				$ifol = $ifol . DIRECTORY_SEPARATOR . $name2 . DIRECTORY_SEPARATOR;
				if (!is_dir($ifol)) mkdir($ifol);
			} else {
				if (!is_dir($ifol)) mkdir($ifol, 0775);
				$ifol = $ifol . DIRECTORY_SEPARATOR . $name1 . DIRECTORY_SEPARATOR;
				if (!is_dir($ifol)) mkdir($ifol, 0775);
				$ifol = $ifol . DIRECTORY_SEPARATOR . $name2 . DIRECTORY_SEPARATOR;
				if (!is_dir($ifol)) mkdir($ifol, 0775);
			}

			// Accept upload if there was no origin, or if it is an accepted origin

			$filetowrite = $ifol . $temp['name'];

			if (file_exists($filetowrite)) unlink($filetowrite);
			move_uploaded_file($temp['tmp_name'], $filetowrite);
			if (file_exists($filetowrite)) {
				$this->lib("wideimage/WideImage", 'wideimage', "inc");
				WideImage::load($filetowrite)->resize(320)->saveToFile($filetowrite);
				return $fldr . "/" . $name1 . "/" . $name2 . "/" . $name;
			} else {
				return 0;
			}
		} else {
			// Notify editor that the upload failed
			//header("HTTP/1.0 500 Server Error");
			return 0;
		}
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

		$tbl_as = $this->apm->tbl_as;

		switch ($iSortCol_0) {
			case 0:
				$sortCol = "$tbl_as.id";
				break;
			case 1:
				$sortCol = "$tbl_as.foto";
				break;
			case 2:
				$sortCol = "$tbl_as.username";
				break;
			case 3:
				$sortCol = "$tbl_as.email";
				break;
			case 4:
				$sortCol = "$tbl_as.nama";
				break;
			case 5:
				$sortCol = "$tbl_as.is_active";
				break;
			case 6:
				$sortCol = "$tbl_as.welcome_message";
				break;
			default:
				$sortCol = "$tbl_as.id";
		}

		if (empty($draw)) $draw = 0;
		if (empty($pagesize)) $pagesize = 10;
		if (empty($page)) $page = 0;

		$keyword = $sSearch;


		$this->status = 200;
		$this->message = 'Berhasil';
		$jenis_count = $this->apm->countPengguna($keyword);
		$jenis_data = $this->apm->getPengguna($page, $pagesize, $sortCol, $sortDir, $keyword);

		foreach ($jenis_data as &$gd) {
			if (isset($gd->foto)) {
				if (!empty($gd->foto)) {
					$gd->foto = '<img src="' . base_url($gd->foto) . '" style="width: 96px; height: auto;" />';
				} else {
					$gd->foto = '<img src="' . base_url('media/pengguna/default.png') . '"  style="width: 96px; height: auto;" />';
				}
			}
			if (isset($gd->is_active)) {
				if (!empty($gd->is_active)) {
					$gd->is_active = '<span class="label label-success">Aktif</span>';
				} else {
					$gd->is_active = '<span class="label label-danger">Tidak Aktif</span>';
				}
				if (!empty($gd->is_notif_interview)) {
					$gd->is_active .= '<span class="label label-info"><i class="fa fa-bell-o"></i> Interview</span>';
				}
			}
		}
		//sleep(3);
		$another = array();
		$this->__jsonDataTable($jenis_data, $jenis_count);
	}
	public function tambah()
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
		$di = $_POST;
		if (!isset($di['username'])) $di['username'] = "";
		if (!isset($di['email'])) $di['email'] = "";
		if (strlen($di['email']) > 1  && strlen($di['username']) > 1) {
			$check = $this->apm->checkusername($di['username']); //1 = sudah digunakan
			if (empty($check)) {
				if (isset($di['password'])) $di['password'] = md5($di['password']);
				$di['foto'] = '';
				$res = $this->apm->set($di);
				if ($res) {
					$last_pengguna_id = $res;
					$this->status = 200;
					$this->message = 'Data baru berhasil ditambahkan';
					$penguna_foto = $this->__uploadFoto($last_pengguna_id);
					if (strlen($penguna_foto) > 4) {
						$du = array();
						$du['foto'] = $penguna_foto;
						$this->apm->update($last_pengguna_id, $du);
						$this->message .= ', Foto profil berhasil diupload';
					}
				} else {
					$this->status = 900;
					$this->message = 'Tidak dapat menyimpan data baru, silakan coba beberapa saat lagi';
				}
			} else {
				$this->status = 104;
				$this->message = 'Kode sudah digunakan, silakan coba kode lain';
			}
		}
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
		$this->status = 200;
		$this->message = 'Berhasil';
		$data = $this->apm->getById($id);
		$this->__json_out($data);
	}
	public function edit()
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
		$du = $_POST;
		if (!isset($du['id'])) $du['id'] = 0;
		$id = (int) $du['id'];
		unset($du['id']);
		if ($id > 0) {
			$check = 0;
			if (isset($du['username'])) {
				$check = $this->apm->checkusername($du['username'], $id); //1 = sudah digunakan
			}

			if (empty($check)) {
				$res = $this->apm->update($id, $du);
				if ($res) {
					$this->status = 200;
					$this->message = 'Perubahan berhasil diterapkan';
					if (isset($d['sess']->admin->nama)) $d['sess']->admin->nama = $du['nama'];
					if (isset($d['sess']->admin->email)) $d['sess']->admin->email = $du['email'];
					if (isset($d['sess']->admin->username)) $d['sess']->admin->username = $du['username'];
				} else {
					$this->status = 901;
					$this->message = 'Tidak dapat melakukan perubahan ke basis data';
				}
			} else {
				$this->status = 104;
				$this->message = 'Username sudah digunakan, silakan coba yang lain';
			}
		} else {
			$this->status = 448;
			$this->message = 'ID Tidak ditemukan';
		}
		$this->__json_out($data);
	}
	public function editpass($id = "")
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
		$id = (int) $id;
		$du = $_POST;
		if (!isset($du['id'])) $du['id'] = 0;
		unset($du['id']);
		if ($id > 0) {
			if (strlen($du['password'])) {
				$du['password'] = md5($du['password']);
				$res = $this->apm->update($id, $du);
				$this->status = 200;
				$this->message = 'Perubahan berhasil diterapkan';
			} else {
				$this->status = 901;
				$this->message = 'Password terlalu pendek';
			}
		} else {
			$this->status = 447;
			$this->message = 'ID Pengguna tidak valid';
		}
		$this->__json_out($data);
	}

	public function hapus($id)
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
		$this->status = 200;
		$this->message = 'Berhasil';
		$res = $this->apm->del($id);
		if (!$res) {
			$this->status = 902;
			$this->message = 'Data gagal dihapus';
		}
		$this->__json_out($data);
	}
	public function edit_foto($id)
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
		$id = (int) $id;
		$du = $_POST;
		if (!isset($du['id'])) $du['id'] = 0;
		if (empty($id)) {
			$id = (int) $du['id'];
			unset($du['id']);
		}
		$pengguna = $this->apm->getById($id);
		if ($id > 0 && isset($pengguna->id)) {
			$penguna_foto = $this->__uploadFoto($pengguna->id);
			if (!empty($penguna_foto)) {
				if (strlen($pengguna->foto) > 4) {
					$foto = SEMEROOT . DIRECTORY_SEPARATOR . $pengguna->foto;
					if (file_exists($foto)) unlink($foto);
				}
				$du = array();
				$du['foto'] = $penguna_foto;
				$res = $this->apm->update($id, $du);
				if ($res) {
					$this->status = 200;
					$this->message = 'Upload foto berhasil';

					if (isset($d['sess']->admin->foto)) $d['sess']->admin->foto = $penguna_foto;
				} else {
					$this->status = 901;
					$this->message = 'Upload foto gagal';
				}
			} else {
				$this->status = 459;
				$this->message = 'Tidak ada file gambar yang terupload';
			}
		} else {
			$this->status = 550;
			$this->message = 'Dont hack this :P';
		}
		$this->__json_out($data);
	}

	//Temporary Select2 di Pengguna API
	public function select2()
	{
		$this->load("api_admin/b_user_model", 'bum');
		$d = $this->__init();
		$keyword = $this->input->request('q');
		$ddata = $this->bum->select2($keyword);
		$datares = array();
		$i = 0;
		foreach ($ddata as $key => $value) {
			$datares["results"][$i++] = array("id" => $value->id, "text" => $value->kode . " - " . $value->fnama);
		}
		header('Content-Type: application/json');
		echo json_encode($datares);
	}

	public function hak_akses()
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
		$this->load('api_admin/a_pengguna_module_model', 'apmm');
		$a_pengguna_id			= $_POST['a_pengguna_id'];
		$a_modules_identifier	= $_POST['a_modules_identifier'];

		$this->apmm->updateModule(array('tmp_active' => 'N'), $a_pengguna_id);

		foreach ($a_modules_identifier as $ami) {
			$arr							= array();
			$arr['a_pengguna_id']			= $a_pengguna_id;
			$arr['a_modules_identifier']	= $ami;
			$arr['rule']					= 'allowed';
			$arr['tmp_active']				= 'Y';

			$check_ami = $this->apmm->check_access($a_pengguna_id, $ami);
			if ($check_ami == 0) {
				$this->apmm->set($arr);
			} else {
				$this->apmm->updateModule($arr, $a_pengguna_id, $ami);
			}
		}

		$res = $this->apmm->delModule($a_pengguna_id);

		if ($res) {
			$this->status 	= 200;
			$this->message 	= 'Berhasil disimpan';
		} else {
			$this->status 	= 901;
			$this->message 	= 'Terjadi kesalahan dalam proses';
		}

		$this->__json_out($data);
	}
	public function pengguna_module()
	{
		$this->load('api_admin/a_pengguna_module_model', 'apmm');
		$d 			= $this->__init();
		$id			= $this->input->post('id');
		$ddata 		= $this->apmm->pengguna_module($id);
		$datares 	= array();
		$i 			= 0;
		foreach ($ddata as $key => $value) {
			$datares[$i++] = $value->a_modules_identifier;
		}
		header('Content-Type: application/json');
		echo json_encode($datares);
	}
	public function cari()
	{
		$keyword = $this->input->request("keyword");
		if (empty($keyword)) $keyword = "";
		$p = new stdClass();
		$p->id = 'NULL';
		$p->text = '-';
		$data = $this->apm->cari($keyword);
		array_unshift($data, $p);
		$this->__json_select2($data);
	}
	public function karyawan($id)
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

		$id = (int) $id;
		if ($id <= 0) {
			$this->status = 487;
			$this->message = 'Invalid ID';
			$this->__json_out($data);
			die();
		}

		$apm = $this->apm->getById($id);
		if (!isset($apm->id)) {
			$this->status = 486;
			$this->message = 'Data dengan ID tersebut tidak ada';
			$this->__json_out($data);
			die();
		}

		if (!empty($apm->is_karyawan)) {
			$this->status = 485;
			$this->message = 'Data dengan ID tersebut telah menjadi karyawan';
			$this->__json_out($data);
			die();
		}

		$du = array();
		$du['is_karyawan'] = 1;
		$this->apm->update($id, $du);

		$this->status = 200;
		$this->message = 'Berhasil';
		$data = $this->apm->getById($id);
		$this->__json_out($data);
	}
}
