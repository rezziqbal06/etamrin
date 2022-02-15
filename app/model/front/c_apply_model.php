<?php
class C_Apply_Model extends SENE_Model{
	public $tbl = 'c_apply';
	public $tbl_as = 'cl';
	public $tbl2 = 'b_lowongan';
	public $tbl2_as = 'bl';
	public $tbl3 = 'a_jabatan';
	public $tbl3_as = 'aj';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}

	public function update($id, $du)
	{
		$this->db->where("id", $id);
		return $this->db->update($this->tbl, $du);
	}

	public function get($is_active = '1')
	{
		$this->db->where_as("$this->tbl_as.is_active", $this->db->esc($is_active));
		$this->db->order_by("$this->tbl_as.id", 'desc');
		return $this->db->get();
	}
	public function getById($id)
	{
		$this->db->where("id", $id);
		return $this->db->from($this->tbl)->get_first();
	}
	public function getByUser($id)
	{
		$this->db->where("b_user_id", $id);
		return $this->db->from($this->tbl)->get_first();
	}
	public function getByUserId($b_user_id)
	{
		$this->db->select_as("$this->tbl3_as.*,$this->tbl2_as.*,$this->tbl_as.*, $this->tbl_as.id", 'id');
		$this->db->select_as("$this->tbl2_as.nama", 'lowongan');
		$this->db->select_as("$this->tbl2_as.nama", 'posisi');
		$this->db->select_as("$this->tbl3_as.nama", 'jabatan');
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'b_lowongan_id', '');
		$this->db->join($this->tbl3, $this->tbl3_as, 'id', $this->tbl2_as, 'a_jabatan_id', '');
		$this->db->where_as("$this->tbl_as.is_active", $this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.b_user_id", $this->db->esc($b_user_id));
		$this->db->order_by("$this->tbl_as.id", 'desc');
		return $this->db->get_first();
	}
	public function check($b_user_id)
	{
		$this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", 'id');
		$this->db->select_as("$this->tbl2_as.nama", 'lowongan');
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'b_lowongan_id', '');
		$this->db->where_as("$this->tbl_as.is_active", $this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.is_process", $this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.b_user_id", $this->db->esc($b_user_id));
		$this->db->order_by("$this->tbl_as.id", 'desc');
		return $this->db->get_first();
	}
	public function checkByUserId($b_user_id){
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where_as("$this->tbl_as.edate", '"'.date("Y-m-d").'"','AND','>=');
		$this->db->where_as("$this->tbl_as.b_user_id", $this->db->esc($b_user_id));
		$this->db->where_as("$this->tbl_as.is_active", $this->db->esc('1'));
		return $this->db->get('',0);
	}
	public function countByUserId($b_user_id){
		$this->db->select_as("COUNT(*)", 'total');
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where_as("$this->tbl_as.b_user_id", $this->db->esc($b_user_id));
		$this->db->where_as("$this->tbl_as.is_active", $this->db->esc('1'));
		$d = $this->db->get_first();
		if(isset($d->total)) return (int) $d->total;
		return 0;
	}
}
