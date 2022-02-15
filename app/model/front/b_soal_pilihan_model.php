<?php
class B_Soal_Pilihan_Model extends SENE_Model {
	var $tbl = 'b_soal_pilihan';
	var $tbl_as = 'bsp';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}

	public function getBySoalId($b_soal_id){
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->where("b_soal_id", $b_soal_id);
		$this->db->order_by('id','asc');
		return $this->db->get('',0);
	}
	public function getBySoalIds($b_soal_ids){
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->where_in("b_soal_id", $b_soal_ids);
		$this->db->order_by('b_soal_id','asc');
		$this->db->order_by('id','asc');
		return $this->db->get('',0);
	}

	public function countBySoalId($b_soal_id){
		$d = $this->db->select_as("COUNT(*)","total",1)->from($this->tbl)->where("b_soal_id",$b_soal_id)->get();
		if(isset($d[0]->total)) return $d[0]->total;
		return 0;
	}
	public function set($d){
		return $this->db->insert($this->tbl,$d);
	}

	public function setMass($ds){
		$this->db->insert_multi($this->tbl, $ds);
	}
	public function update($id,$d){
		$this->db->where("id",$id);
		return $this->db->update($this->tbl,$d,0);
	}
	public function updateFolder($foldername,$du){
		$this->db->where("folder",$foldername);
		return $this->db->update($this->tbl,$du,0);
	}
	public function del($id){
		$this->db->where("id",$id);
		return $this->db->delete($this->tbl);
	}
	public function delBySoalId($b_soal_id){
		$this->db->where("b_soal_id",$b_soal_id);
		return $this->db->delete($this->tbl);
	}
	public function getById($id){
		$this->db->where("id",$id);
		return $this->db->from($this->tbl)->get_first();
	}
}
