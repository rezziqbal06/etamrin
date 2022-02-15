<?php
//admin
class A_Kelas_Model extends Sene_Model
{
	var $tbl = 'a_kelas';
	var $tbl_alias = 'akel';
	var $tbl_as = 'akel';
	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}

	public function getAll($page = 0, $pagesize = 10, $sortCol = "identifier", $sortDir = "ASC", $keyword = '', $sdate = '', $edate = '')
	{
		$this->db->flushQuery();
		$this->db->select_as("id, nama, wali_kelas, deskripsi", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		if (strlen($keyword) > 1) {
			$this->db->where("nama", $keyword, "OR", "%like%", 1, 0);
			$this->db->where("wali_kelas", $keyword, "OR", "%like%", 0, 0);
			$this->db->where("deskripsi", $keyword, "OR", "%like%", 0, 1);
		}
		$this->db->order_by($sortCol, $sortDir)->limit($page, $pagesize);
		return $this->db->get("object", 0);
	}
	public function countAll($keyword = '', $sdate = '', $edate = '')
	{
		$this->db->flushQuery();
		$this->db->select_as("COUNT(*)", "jumlah", 0);
		if (strlen($keyword) > 1) {
			$this->db->where("nama", $keyword, "OR", "%like%", 1, 0);
			$this->db->where("wali_kelas", $keyword, "OR", "%like%", 0, 0);
			$this->db->where("deskripsi", $keyword, "OR", "%like%", 0, 1);
		}
		$d = $this->db->from($this->tbl)->get_first("object", 0);
		if (isset($d->jumlah)) return $d->jumlah;
		return 0;
	}
	public function getAllDs()
	{
		$sql = "SELECT * FROM `$this->tbl` WHERE `is_visible` = 1 ORDER BY priority ASC, `has_submenu` ASC";
		return $this->db->query($sql);
	}
	public function getAllParent()
	{
		$sql = "SELECT * FROM `$this->tbl` WHERE `is_visible` = 1 AND `children_identifier` IS NULL ORDER BY priority ASC, `has_submenu` ASC";
		//die($sql);
		return $this->db->query($sql);
	}
	public function getChild($children_identifier)
	{
		$sql = "SELECT * FROM `$this->tbl` WHERE `is_visible` = 1 AND `children_identifier` LIKE " . $this->db->esc($children_identifier) . " ORDER BY priority ASC, `has_submenu` ASC";
		return $this->db->query($sql);
	}
	public function getAllVisible()
	{
		//return $this->db->from($this->tbl)->where("is_visible",1)->order_by("priority","asc")->get();
		return $this->db->from($this->tbl)->order_by("priority", "asc")->get();
	}
	public function getAllVisibleParent()
	{
		return $this->db->from($this->tbl)->order_by("priority", "asc")->where_as("children_identifier", "IS NULL")->get("object", 0);
	}
	public function getIdentifierAll()
	{
		//return $this->db->from($this->tbl)->where("is_visible",1)->order_by("priority","asc")->get();
		return $this->db->select("identifier")->from($this->tbl)->order_by("priority", "asc")->get();
	}
	public function getById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get_first();
	}
	public function getParent($identifier)
	{
		$d = $this->db->select_as("COALESCE(children_identifier,'')", "children_identifier", 1)->from($this->tbl)->where("identifier", $identifier)->order_by("priority", "asc")->get_first();
		if (isset($d[0]->children_identifier)) return $d[0]->children_identifier;
		return "";
	}

	public function getChildModules($id = '')
	{
		$this->db->where_as('is_visible', $this->db->esc('1'));
		$this->db->where_as('is_active', $this->db->esc('1'));
		$this->db->where_as('COALESCE(children_identifier,"")', $this->db->esc($id));
		$this->db->order_by('priority', 'ASC');
		return $this->db->get('', 0);
	}
	public function getVisibleAndActive()
	{
		$this->db->where('is_active', '1');
		$this->db->where('is_visible', '1');
		$this->db->order_by('identifier', 'asc');
		return $this->db->get();
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
}
