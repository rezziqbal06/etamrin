<?php
class D_Soal_Model extends SENE_Model{
	public $tbl = 'd_soal';
	public $tbl_as = 'ds';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}

	public function trans_start(){
		$r = $this->db->autocommit(0);
		if($r) return $this->db->begin();
		return false;
	}
	public function trans_commit(){
		return $this->db->commit();
	}
	public function trans_rollback(){
		return $this->db->rollback();
	}
	public function trans_end(){
		return $this->db->autocommit(1);
	}

	public function set($d){
		$this->db->insert($this->tbl,$d,0,0);
		return $this->db->lastId();
	}
	public function update($id,$d){
		$this->db->where("id",$id);
		return $this->db->update($this->tbl,$d);
	}
	public function del($id){
		$this->db->where("id",$id);
		return $this->db->delete($this->tbl);
	}
  public function get($is_active='1'){
    $this->db->where_as("$this->tbl_as.is_active",$this->db->esc($is_active));
    $this->db->order_by("$this->tbl_as.id",'desc');
    return $this->db->get();
  }
	public function getById($id){
		$this->db->where("id",$id);
		return $this->db->from($this->tbl)->get_first();
	}
	public function getByEmail($email){
		$this->db->where("email",$email);
		return $this->db->from($this->tbl)->get_first();
	}
	public function getByUjiId($c_uji_id){
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->where_as("$this->tbl_as.c_uji_id",$this->db->esc($c_uji_id));
		$this->db->order_by("$this->tbl_as.id");
		//$this->db->order_by("$this->tbl_as.urutan");
		return $this->db->get();
	}
}
