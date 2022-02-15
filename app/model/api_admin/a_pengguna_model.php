<?php
class A_Pengguna_Model extends SENE_Model
{
	public $tbl = 'a_pengguna';
	public $tbl_as = 'ap';
	public $tbl2 = 'a_pengguna_jabatan';
	public $tbl2_as = 'j';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}
	public function getAll($page = 0, $pagesize = 10, $sortCol = "id", $sortDir = "ASC", $keyword = "", $sdate = "", $edate = "")
	{
		$this->db->flushQuery();
		$this->db->select('id')
			->select('foto')
			->select('username')
			->select('email')
			->select('nama')
			->select('welcome_message')
			->select('is_active');
		$this->db->from($this->tbl, $this->tbl_as);
		if (strlen($keyword) > 1) {
			$this->db->where("username", $keyword, "OR", "%like%", 1, 0);
			$this->db->where("email", $keyword, "OR", "%like%", 0, 1);
		}
		$this->db->order_by($sortCol, $sortDir)->limit($page, $pagesize);
		return $this->db->get("object", 0);
	}
	public function countAll($keyword = "", $sdate = "", $edate = "")
	{
		$this->db->flushQuery();
		$this->db->select_as("COUNT(*)", "jumlah", 0);
		if (strlen($keyword) > 1) {
			$this->db->where("username", $keyword, "OR", "%like%", 1, 0);
			$this->db->where("email", $keyword, "OR", "%like%", 0, 1);
		}
		$d = $this->db->from($this->tbl)->get_first("object", 0);
		if (isset($d->jumlah)) {
			return $d->jumlah;
		}
		return 0;
	}
	public function getById($id)
	{
		$this->db->where("id", $id);
		return $this->db->get_first();
	}
	public function getByUtype($utype)
	{
		$this->db->where("utype", $utype);
		return $this->db->get_first();
	}
	public function set($di)
	{
		if (!is_array($di)) {
			return 0;
		}
		$this->db->insert($this->tbl, $di, 0, 0);
		return $this->db->last_id;
	}
	public function update($id, $du)
	{
		if (!is_array($du)) {
			return 0;
		}
		$this->db->where("id", $id);
		return $this->db->update($this->tbl, $du, 0);
	}
	public function del($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete($this->tbl);
	}
	public function checkusername($username, $id = 0)
	{
		$this->db->select_as("COUNT(*)", "jumlah", 0);
		$this->db->where("username", $username);
		if (!empty($id)) {
			$this->db->where("id", $id, 'AND', '!=');
		}
		$d = $this->db->from($this->tbl)->get_first("object", 0);
		if (isset($d->jumlah)) {
			return $d->jumlah;
		}
		return 0;
	}
	public function checkKode($id = 0)
	{
		$this->db->select_as("COUNT(*)", "jumlah", 0);
		$this->db->where("id", $id);
		$d = $this->db->from($this->tbl)->get_first("object", 0);
		if (isset($d->jumlah)) {
			return $d->jumlah;
		}
		return 0;
	}

	public function getPengguna($page = 0, $pagesize = 10, $sortCol = "id", $sortDir = "ASC", $keyword = "", $a_company_id = "", $edate = "")
	{
		$this->db->flushQuery();
		$this->db->select_as("$this->tbl_as.id", 'id', 0)
			->select_as("$this->tbl_as.foto", 'foto', 0)
			->select_as("$this->tbl_as.username", 'username', 0)
			->select_as("$this->tbl_as.email", 'email', 0)
			->select_as("$this->tbl_as.nama", 'nama', 0)
			->select_as("$this->tbl_as.is_active", 'is_active', 0)
			->select_as("$this->tbl_as.is_notif_interview",'is_notif_interview',0);
		;
		$this->db->from($this->tbl, $this->tbl_as);
		// $this->db->join($this->tbl4, $this->tbl4_as, 'id', $this->tbl_as, 'a_departemen_id','left');
		// $this->db->where_as("$this->tbl_as.is_karyawan", $this->db->esc(0));
		if (strlen($keyword) > 0) {
			$this->db->where_as("$this->tbl_as.username", $keyword, "OR", "%like%", 1, 0);
			$this->db->where_as("$this->tbl_as.nama", $keyword, "OR", "%like%", 0, 0);
			// $this->db->where_as("$this->tbl4_as.nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, "OR", "%like%", 0, 1);
		}
		$this->db->order_by($sortCol, $sortDir)->limit($page, $pagesize);
		return $this->db->get("object", 0);
	}
	public function countPengguna($keyword = "", $a_company_id = "", $edate = "")
	{
		$this->db->flushQuery();
		$this->db->select_as("COUNT(*)", "jumlah", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		// $this->db->join($this->tbl4, $this->tbl4_as, 'id', $this->tbl_as, 'a_departemen_id','left');
		// $this->db->where_as("$this->tbl_as.is_karyawan", $this->db->esc(0));
		if (strlen($keyword) > 0) {
			$this->db->where_as("$this->tbl_as.username", $keyword, "OR", "%like%", 1, 0);
			$this->db->where_as("$this->tbl_as.nama", $keyword, "OR", "%like%", 0, 0);
			// $this->db->where_as("$this->tbl4_as.nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, "OR", "%like%", 0, 1);
		}
		$d = $this->db->get_first("object", 0);
		if (isset($d->jumlah)) {
			return $d->jumlah;
		}
		return 0;
	}
	public function getKaryawan($page = 0, $pagesize = 10, $sortCol = "id", $sortDir = "ASC", $keyword = "", $a_company_id = "", $a_departemen_id = "")
	{
		$this->db->flushQuery();

		$this->db->select_as("$this->tbl_as.id", 'id', 0);
		$this->db->select_as("$this->tbl_as.nip", 'nip', 0);
		$this->db->select_as("$this->tbl_as.nama", 'nama', 0);
		$this->db->select_as("$this->tbl_as.a_jabatan_nama", 'a_jabatan_nama', 0);
		$this->db->select_as("$this->tbl_as.a_company_nama", 'a_company_nama', 0);
		$this->db->select_as("COALESCE($this->tbl4_as.nama,'-')", 'departemen', 0);
		$this->db->select_as("$this->tbl_as.foto", 'foto', 0);
		$this->db->select_as("$this->tbl_as.karyawan_status", 'karyawan_status', 0);
		$this->db->select_as("$this->tbl_as.is_active", 'is_active', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl4, $this->tbl4_as, 'id', $this->tbl_as, 'a_departemen_id', 'left');
		$this->db->where_as("$this->tbl_as.is_karyawan", $this->db->esc(1));
		if (strlen($a_company_id)) {
			$this->db->where_as("$this->tbl_as.a_company_id", $this->db->esc($a_company_id));
		}
		if (strlen($a_departemen_id)) {
			$this->db->where_as("$this->tbl_as.a_departemen_id", $this->db->esc($a_departemen_id));
		}
		if (strlen($keyword) > 0) {
			$this->db->where_as("$this->tbl_as.username", $keyword, "OR", "%like%", 1, 0);
			$this->db->where_as("$this->tbl_as.nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("COALESCE($this->tbl4_as.nama,'')", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.a_jabatan_nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.a_company_nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, "OR", "%like%", 0, 1);
		}
		$this->db->order_by($sortCol, $sortDir)->limit($page, $pagesize);
		return $this->db->get("object", 0);
	}
	public function countKaryawan($keyword = "", $a_company_id = "", $a_departemen_id = "")
	{
		$this->db->flushQuery();
		$this->db->select_as("COUNT(*)", "jumlah", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl4, $this->tbl4_as, 'id', $this->tbl_as, 'a_departemen_id', 'left');
		$this->db->where_as("$this->tbl_as.is_karyawan", $this->db->esc(1));
		if (strlen($a_company_id)) {
			$this->db->where_as("$this->tbl_as.a_company_id", $this->db->esc($a_company_id));
		}
		if (strlen($a_departemen_id)) {
			$this->db->where_as("$this->tbl_as.a_departemen_id", $this->db->esc($a_departemen_id));
		}
		if (strlen($keyword) > 0) {
			$this->db->where_as("$this->tbl_as.username", $keyword, "OR", "%like%", 1, 0);
			$this->db->where_as("$this->tbl_as.nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("COALESCE($this->tbl4_as.nama,'')", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.a_jabatan_nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.a_company_nama", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, "OR", "%like%", 0, 1);
		}
		$d = $this->db->get_first("object", 0);
		if (isset($d->jumlah)) {
			return $d->jumlah;
		}
		return 0;
	}
	public function getResepDokter($keyword = "", $a_company_id = "")
	{
		$this->db->select_as("$this->tbl_as.*, COALESCE($this->tbl2_as.nama,'dokter')", 'jabatan', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'a_jabatan_id', 'left');
		$this->db->where_in("LOWER(COALESCE($this->tbl2_as.nama,'terapis'))", array('dokter'));
		if (strlen($a_company_id)) $this->db->where_as("$this->tbl_as.a_company_id", $a_company_id, 'AND', '=', 0, 0);
		if (strlen($keyword)) {
			$this->db->where_as("$this->tbl_as.nama", $keyword, 'OR', 'like%%', 1, 0);
			$this->db->where_as("$this->tbl_as.username", $keyword, 'OR', 'like%%', 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, 'OR', 'like%%', 0, 0);
			$this->db->where_as("$this->tbl_as.a_company_nama", $keyword, 'OR', 'like%%', 0, 1);
		}
		$this->db->order_by("$this->tbl_as.nama", 'asc');
		$this->db->order_by("COALESCE($this->tbl2_as.nama,'dokter')", 'asc');
		return $this->db->get('', 0);
	}
	public function cari($keyword = "")
	{
		$this->db->select_as("$this->tbl_as.id", "id", 0);
		$this->db->select_as("CONCAT($this->tbl_as.nama,' (',$this->tbl_as.a_company_nama,')')", "text", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		if (strlen($keyword) > 0) {
			$this->db->where_as("$this->tbl_as.nama", ($keyword), "OR", "LIKE%%", 1, 0);
			$this->db->where_as("$this->tbl_as.a_company_nama", ($keyword), "OR", "LIKE%%", 0, 0);
			$this->db->where_as("$this->tbl_as.nip", ($keyword), "OR", "LIKE%%", 0, 1);
		}
		$this->db->order_by("$this->tbl_as.nama", "asc");
		$this->db->order_by("$this->tbl_as.a_company_nama", "asc");
		return $this->db->get('', 0);
	}

	public function getTerapisByCompanyId($keyword = "", $a_company_id = "", $who_first = "")
	{
		$this->db->select_as("$this->tbl_as.*, COALESCE($this->tbl2_as.nama,'terapis')", 'jabatan', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'a_jabatan_id', 'left');
		$this->db->where_in("LOWER(COALESCE($this->tbl2_as.nama,'terapis'))", array('dokter', 'terapis', 'perawat'));
		if (strlen($a_company_id)) $this->db->where_as("$this->tbl_as.a_company_id", $a_company_id, 'AND', '=', 0, 0);
		if (strlen($keyword)) {
			$this->db->where_as("$this->tbl_as.nama", $keyword, 'OR', 'like%%', 1, 0);
			$this->db->where_as("$this->tbl_as.username", $keyword, 'OR', 'like%%', 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, 'OR', 'like%%', 0, 0);
			$this->db->where_as("$this->tbl_as.a_company_nama", $keyword, 'OR', 'like%%', 0, 1);
		}
		$this->db->order_by("$this->tbl_as.nama", 'asc');
		$this->db->order_by("COALESCE($this->tbl2_as.nama,'terapis')", 'asc');
		return $this->db->get('', 0);
	}

	public function getJagaDokter($keyword = "", $a_company_id = "")
	{
		$this->db->select_as("$this->tbl_as.*, COALESCE($this->tbl2_as.nama,'dokter')", 'jabatan', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'a_jabatan_id', 'left');
		$this->db->where_in("LOWER(COALESCE($this->tbl2_as.nama,'terapis'))", array('dokter'));
		if (strlen($a_company_id)) $this->db->where_as("$this->tbl_as.a_company_id", $a_company_id, 'AND', '=', 0, 0);
		if (strlen($keyword)) {
			$this->db->where_as("$this->tbl_as.nama", $keyword, 'OR', 'like%%', 1, 0);
			$this->db->where_as("$this->tbl_as.username", $keyword, 'OR', 'like%%', 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, 'OR', 'like%%', 0, 0);
			$this->db->where_as("$this->tbl_as.a_company_nama", $keyword, 'OR', 'like%%', 0, 1);
		}
		$this->db->order_by("$this->tbl_as.nama", 'asc');
		$this->db->order_by("COALESCE($this->tbl2_as.nama,'dokter')", 'asc');
		return $this->db->get('', 0);
	}
	public function getDokterByCompanyId($a_company_id)
	{
		$this->db->select_as("$this->tbl_as.id", 'id', 0);
		$this->db->select_as("$this->tbl_as.nama", 'nama', 0);
		$this->db->select_as("$this->tbl_as.a_jabatan_nama", 'jabatan', 0);
		$this->db->select_as("$this->tbl_as.a_company_nama", 'penempatan', 0);
		$this->db->select_as("$this->tbl_as.nip", 'nip', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where_as("LOWER(a_jabatan_nama)", ('dokter'), 'AND', 'LIKE%%');
		$this->db->where("a_company_id", $a_company_id);
		$this->db->where("is_active", '1');
		$this->db->order_by("$this->tbl_as.nama", 'asc');
		return $this->db->get('', 0);
	}
	public function check($a_jabatan_id,$email){
		$this->db->where('a_jabatan_id',$a_jabatan_id);
		$this->db->where('email',$email);
		return $this->db->get_first();
	}
	public function getIsNotifInterview(){
		$this->db->where('is_notif_interview','1');
		$this->db->group_by('email');
		return $this->db->get();
	}
}
