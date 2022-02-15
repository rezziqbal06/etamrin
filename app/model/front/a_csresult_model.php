<?php
class A_CSResult_Model extends JI_Model{
	var $tbl = 'a_csresult';
	var $tbl_as = 'acsr';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}
	public function getById($id){
		$this->db->where('id', $id);
		$this->db->order_by('id','asc');
		return $this->db->get_first();
	}
	public function getByNilai($id){
		$this->db->where('id', $id);
		$this->db->order_by('id','asc');
		return $this->db->get_first();
	}
}
