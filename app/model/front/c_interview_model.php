<?php
class C_Interview_Model extends JI_Model
{
	public $tbl = 'c_interview';
	public $tbl_as = 'ci';
	public $tbl2 = 'c_apply';
	public $tbl2_as = 'cl';
	public $tbl3 = 'b_lowongan';
	public $tbl3_as = 'bl';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}
	public function validateToken1($token)
	{
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where_as("$this->tbl_as.a_pengguna_id1_token", 'IS NOT NULL');
		$this->db->where_as("$this->tbl_as.a_pengguna_id1_token", $this->db->esc($token));
		return $this->db->get_first('', 0);
	}
	public function validateToken2($token)
	{
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where_as("$this->tbl_as.a_pengguna_id2_token", 'IS NOT NULL');
		$this->db->where_as("$this->tbl_as.a_pengguna_id2_token", $this->db->esc($token));
		return $this->db->get_first('', 0);
	}
	public function getById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get_first();
	}
	public function getByApplyId($c_apply_id)
	{
		$this->db->where('c_apply_id', $c_apply_id);
		$this->db->where_in('status_no', array(4, 9));
		$this->db->order_by('status_no', 'asc');
		$this->db->order_by('tglwaktu', 'asc');
		$this->db->group_by('utype');
		$this->db->limit(0, 2);
		return $this->db->get();
	}
	public function getSelesaiByApplyId($c_apply_id)
	{
		$this->db->where('status_no', '9');
		$this->db->group_by('utype');
		$this->db->order_by('utype', 'asc');
		$this->db->limit(0, 2);
		return $this->db->get();
	}
	public function getByApplyAndUtype($c_apply_id, $utype)
	{
		$this->db->where('c_apply_id', $c_apply_id);
		$this->db->where('utype', $utype);
		return $this->db->get_first();
	}
}
