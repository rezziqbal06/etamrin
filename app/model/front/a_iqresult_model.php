<?php
class A_IQResult_Model extends JI_Model{
	var $tbl = 'a_iqresult';
	var $tbl_as = 'aiqr';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}
	public function getById($id){
		$this->db->where('id', $id);
		$this->db->order_by('id','asc');
		return $this->db->get_first();
	}
	public function getByNilai($nilai){
		$this->db->order_by('ABS(nilai - '.$this->db->esc($nilai).')','asc');
		return $this->db->get_first();
	}
}
