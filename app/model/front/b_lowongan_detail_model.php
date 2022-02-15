<?php
class B_Lowongan_Detail_Model extends SENE_Model{
	Public $tbl = 'b_lowongan_detail';
	Public $tbl_as = 'bld';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}

	public function getById($id){
		$this->db->where("id",$id);
		return $this->db->from($this->tbl)->get_first();
	}

	public function getByLowonganId($b_lowongan_id){
		$this->db->where("b_lowongan_id",$b_lowongan_id);
		return $this->db->from($this->tbl)->get();
	}
}
