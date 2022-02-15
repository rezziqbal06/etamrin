<?php
class Sumber extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("api_admin/a_sumber_pelamar_model", 'aspm');
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
        $sSearch = $this->input->request("sSearch");
        $sEcho = $this->input->post("sEcho");
        $page = $this->input->post("iDisplayStart");
        $pagesize = $this->input->request("iDisplayLength");

        $iSortCol_0 = $this->input->post("iSortCol_0");
        $sSortDir_0 = $this->input->post("sSortDir_0");

        $sSortDir_0 = $this->input->post("sSortDir_0");

        $is_active = $this->input->post('is_active');
        if (empty($is_active)) $is_active = "";

        $in_utype = array();
        if (!empty($utype)) {
            $in_utype = explode(',', $utype);
        }

        $sortCol = "id";
        $sortDir = strtoupper($sSortDir_0);
        if (empty($sortDir)) {
            $sortDir = "DESC";
        }
        if (strtolower($sortDir) != "desc") {
            $sortDir = "ASC";
        }

        switch ($iSortCol_0) {
            case 0:
                $sortCol = "id";
                break;
            case 1:
                $sortCol = "nama";
                break;
            case 2:
                $sortCol = "link";
                break;
            case 3:
                $sortCol = "deskripsi";
                break;
            case 4:
                $sortCol = "is_active";
                break;
            default:
                $sortCol = "id";
        }

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


        $this->status = 200;
        $this->message = 'Berhasil';
        $dcount = $this->aspm->countAll($keyword, $is_active);
        $ddata = $this->aspm->getAll($page, $pagesize, $sortCol, $sortDir, $keyword, $is_active);

        foreach ($ddata as &$gd) {
            if (isset($gd->is_active)) {
                if ($gd->is_active == 1) {
                    $gd->is_active = '<span class="label label-success">Aktif</span>';
                } else {
                    $gd->is_active = '<span class="label label-default">Tidak Aktif</span>';
                }
                if (isset($gd->is_freshg)) {
                    if (!empty($gd->is_freshg)) {
                        $gd->is_freshg = ' <span class="label label-info">Iya</span>';
                    } else {
                        $gd->is_freshg = ' <span class="label label-default">Tidak</span>';
                    }
                }
            }
        }
        //sleep(3);
        $another = array();
        $this->__jsonDataTable($ddata, $dcount);
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
        //Mengambil input post
        $di = $_POST;


        if (!isset($di['nama'])) {
            $di['nama'] = "";
        }
        if (strlen($di['nama']) > 1) {

            $res = $this->aspm->set($di);
            if ($res) {

                $this->status = 200;
                $this->message = 'Data baru berhasil ditambahkan';
            } else {
                $this->status = 900;
                $this->message = 'Tidak dapat menyimpan data baru, silakan coba beberapa saat lagi';
            }
        }
        $this->__json_out($data);
    }
    public function detail($id)
    {
        $id = (int) $id;
        $d = $this->__init();
        $data = new stdClass();
        if (!$this->admin_login && empty($id)) {
            $this->status = 400;
            $this->message = 'Session telah expired, silakan login lagi';
            header("HTTP/1.0 400 Harus login");
            $this->__json_out($data);
            die();
        }
        $this->status = 200;
        $this->message = 'Berhasil';
        $data = $this->aspm->getById($id);
        if (!isset($data->id)) {
            $this->status = 1004;
            $this->message = 'Data Company tidak ditemukan';
        }
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
        if (!isset($du['id'])) {
            $du['id'] = 0;
        }
        $id = (int) $du['id'];
        unset($du['id']);

        if (!isset($du['nama'])) {
            $du['nama'] = "";
        }

        if ($id > 0 && strlen($du['nama']) > 1) {
            $aspm = $this->aspm->getById($id);
            if (!isset($aspm->id)) {
                $this->status = 401;
                $this->message = "Sumber tidak ditemukan";
                $this->__json_out($data);
                die();
            }

            $res = $this->aspm->update($id, $du);
            if ($res) {

                $this->status = 200;
                $this->message = 'Perubahan berhasil diterapkan';
            } else {
                $this->status = 901;
                $this->message = 'Tidak dapat melakukan perubahan ke basis data';
            }
        } else {
            $this->status = 444;
            $this->message = 'One or more parameter required';
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
        $aspm = $this->aspm->getById($id);
        if (!isset($aspm->id)) {
            $this->status = 401;
            $this->message = "Sumber tidak ditemukan";
            $this->__json_out($data);
            die();
        }
        $res = $this->aspm->del($id);
        if (!$res) {
            $this->status = 902;
            $this->message = 'Data gagal dihapus';
        }
        $this->__json_out($data);
    }

    public function select2()
    {
        $d = $this->__init();
        $keyword = $this->input->request('q');
        //die($keyword);
        $ddata = $this->aspm->select2($keyword);
        $datares = array();
        $i = 0;
        foreach ($ddata as $key => $value) {
            $datares["results"][$i++] = array("id" => $value->id, "text" => $value->kode . " - " . $value->nama);
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
        $data = $this->aspm->cari($keyword);
        array_unshift($data, $p);
        $this->__json_select2($data);
    }
}
