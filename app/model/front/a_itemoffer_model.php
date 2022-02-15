<?php
class A_Itemoffer_Model extends JI_Model
{
	var $tbl = 'a_itemoffer';
	var $tbl_as = 'aio';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}
	public function getById($id)
	{
		$this->db->where('id', $id);
		$this->db->order_by('id', 'asc');
		return $this->db->get_first();
	}
	public function getAll()
	{
		$this->db->order_by('utype', 'asc');
		return $this->db->get();
	}
}
