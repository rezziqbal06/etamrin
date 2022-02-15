<?php
class B_User_Model extends SENE_Model
{
	var $tbl = 'b_user';
	var $tbl_as = 'bu';
	var $tbl2 = 'a_kelas';
	var $tbl2_as = 'ak';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}


	public function getAll($page = 0, $pagesize = 10, $sortCol = "id", $sortDir = "ASC", $keyword = '', $filters = array(), $utype = '', $is_active = '')
	{
		$this->db->flushQuery();
		$this->db->select_as("$this->tbl_as.id", 'id', 0);
		$this->db->select_as("$this->tbl_as.fnama", 'nama', 0);
		$this->db->select_as("COALESCE($this->tbl2_as.nama, '-')", 'kelas', 0);
		$this->db->select_as("$this->tbl_as.kode", 'nis', 0);
		// $this->db->select_as("$this->tbl_as.email", 'email', 0);
		// $this->db->select_as("$this->tbl_as.telp", 'telp', 0);
		// $this->db->select_as("$this->tbl_as.bdate", 'bdate', 0);
		// $this->db->select_as("CONCAT($this->tbl_as.kabkota,', ',$this->tbl_as.provinsi)", 'domisili', 0);
		// $this->db->select_as("$this->tbl_as.cdate", 'cdate', 0);
		// $this->db->select_as("$this->tbl_as.is_active", 'is_active', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'a_kelas_id');
		if (strlen($keyword) > 0) {
			$this->db->where_as("$this->tbl_as.fnama", $keyword, "OR", "%like%", 1, 0);
			$this->db->where_as("$this->tbl_as.kode", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("COALESCE($this->tbl2_as.nama,'')", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, "OR", "%like%", 0, 1);
		}
		$this->db->order_by($sortCol, $sortDir)->limit($page, $pagesize);
		return $this->db->get("object", 0);
	}
	public function countAll($keyword = '', $filters = array(), $utype = '', $is_active = '')
	{
		$this->db->flushQuery();
		$this->db->select_as("COUNT(DISTINCT $this->tbl_as.id)", "jumlah", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'a_kelas_id');
		if (strlen($keyword) > 0) {
			$this->db->where_as("$this->tbl_as.fnama", $keyword, "OR", "%like%", 1, 0);
			$this->db->where_as("$this->tbl_as.kode", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("COALESCE($this->tbl2_as.nama,'')", $keyword, "OR", "%like%", 0, 0);
			$this->db->where_as("$this->tbl_as.email", $keyword, "OR", "%like%", 0, 1);
		}
		$d = $this->db->get_first("object", 0);
		if (isset($d->jumlah)) return $d->jumlah;
		return 0;
	}

	public function getById($id)
	{
		$this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", "id", 0);
		$this->db->select_as($this->db->__decrypt("$this->tbl_as.noktp"), "noktp", 0);
		$this->db->select_as($this->db->__decrypt("$this->tbl_as.nosim"), "nosim", 0);
		$this->db->select_as($this->db->__decrypt("$this->tbl_as.npwp"), "npwp", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where("id", $id);
		return $this->db->get_first();
	}
	public function set($di)
	{
		if (!is_array($di)) return 0;
		return $this->db->insert($this->tbl, $di, 0, 0);
	}
	public function update($id, $du)
	{
		if (!is_array($du)) return 0;
		$this->db->where("id", $id);
		return $this->db->update($this->tbl, $du, 0);
	}
	public function del($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete($this->tbl);
	}

	public function trans_start()
	{
		$r = $this->db->autocommit(0);
		if ($r) return $this->db->begin();
		return false;
	}
	public function trans_commit()
	{
		return $this->db->commit();
	}
	public function trans_rollback()
	{
		return $this->db->rollback();
	}
	public function trans_end()
	{
		return $this->db->autocommit(1);
	}

	public function getLastId()
	{
		$this->db->select_as("MAX($this->tbl_as.id)+1", "last_id", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$d = $this->db->get_first('', 0);
		if (isset($d->last_id)) return $d->last_id;
		return 0;
	}

	public function get()
	{
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where("is_active", 1);
		return $this->db->get();
	}
}
