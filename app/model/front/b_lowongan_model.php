<?php
class B_Lowongan_Model extends SENE_Model
{
	public $tbl = 'b_lowongan';
	public $tbl_as = 'bl';
	public $tbl2 = 'a_jabatan';
	public $tbl2_as = 'aj';
	public $tbl3 = 'a_company';
	public $tbl3_as = 'ac';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
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

	public function set($d)
	{
		$this->db->insert($this->tbl, $d, 0, 0);
		return $this->db->lastId();
	}
	public function update($id, $d)
	{
		$this->db->where("id", $id);
		return $this->db->update($this->tbl, $d);
	}
	public function del($id)
	{
		$this->db->where("id", $id);
		return $this->db->delete($this->tbl);
	}
	public function get()
	{
		$this->db->where_as("$this->tbl_as.is_deleted", $this->db->esc('0'));
		$this->db->where_as("$this->tbl_as.is_active", $this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.sdate", "('" . date("Y-m-d 00:00:00") . "')", 'AND', '>=');
		$this->db->where_as("$this->tbl_as.edate", "('" . date("Y-m-d 23:59:59") . "')", 'AND', '<=');
		$this->db->order_by("$this->tbl_as.id", 'desc');
		return $this->db->get();
	}
	public function getByFilter($sdate = "", $edate = "", $keyword = "")
	{
		$this->db->where_as("$this->tbl_as.is_deleted", $this->db->esc('0'));
		$this->db->where_as("$this->tbl_as.is_active", $this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.is_favorite", $this->db->esc('1'));
		if (strlen($sdate) >= 10) {
			$this->db->where_as("DATE($this->tbl_as.sdate)", "DATE('" . $sdate . "')", 'AND', '<=');
		}
		if (strlen($edate) >= 10) {
			$this->db->where_as("DATE($this->tbl_as.edate)", "DATE('" . $edate . "')", 'AND', '>=');
		}
		if (strlen($keyword) > 1) {
			$this->db->where_as("COALESCE($this->tbl_as.nama,'-')", $keyword, "OR", "%like%", 1, 0);
			$this->db->where_as("COALESCE($this->tbl_as.deskripsi,'-')", $keyword, "OR", "%like%", 0, 1);
		}
		$this->db->order_by("$this->tbl_as.id", 'desc');
		$this->db->limit(9);
		return $this->db->get('', 0);
	}
	public function getById($id)
	{
		$this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", 'id', 0);
		$this->db->select_as("COALESCE($this->tbl2_as.nama)", 'jabatan_nama', 0);
		$this->db->select_as("COALESCE($this->tbl3_as.lok_area)", 'lok_area', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, "id", $this->tbl_as, "a_jabatan_id");
		$this->db->join($this->tbl3, $this->tbl3_as, "id", $this->tbl_as, "a_company_id");
		$this->db->where("$this->tbl_as.id", $id);
		$this->db->where_as("$this->tbl_as.is_deleted", $this->db->esc('0'));
		return $this->db->from($this->tbl)->get_first();
	}
	public function getSimiliar($a_jabatan_id, $id)
	{
		$this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", 'id', 0);
		$this->db->select_as("COALESCE($this->tbl2_as.nama)", 'jabatan_nama', 0);
		$this->db->select_as("COALESCE($this->tbl3_as.lok_area)", 'lok_area', 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, "id", $this->tbl_as, "a_jabatan_id");
		$this->db->join($this->tbl3, $this->tbl3_as, "id", $this->tbl_as, "a_company_id");
		$this->db->where("$this->tbl_as.id", $id, "AND", "<>");
		$this->db->where("$this->tbl_as.a_jabatan_id", $a_jabatan_id);
		$this->db->where_as("$this->tbl_as.is_deleted", $this->db->esc('0'));
		return $this->db->from($this->tbl)->get();
	}

	public function getAll(){
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where_as("$this->tbl_as.is_deleted", $this->db->esc('0'));
		$this->db->order_by('nama','asc');
		return $this->db->get();
	}
}
