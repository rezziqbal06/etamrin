<?php
class C_Uji_Lowongan_Model extends SENE_Model{
	public $tbl = 'c_uji_lowongan';
	public $tbl_as = 'cul';
	public $tbl2 = 'c_uji';
	public $tbl2_as = 'cu';

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
	public function getByLowonganId($b_lowongan_id){
		$this->db->select_as("$this->tbl2_as.id",'id');
		$this->db->select_as("$this->tbl2_as.nama",'nama');
		$this->db->select_as("$this->tbl2_as.ket",'ket');
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->join($this->tbl2,$this->tbl2_as,'id',$this->tbl_as,'c_uji_id','');
		$this->db->where_as("$this->tbl2_as.is_active",$this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.b_lowongan_id",$this->db->esc($b_lowongan_id));
		$this->db->group_by("$this->tbl_as.id");
		return $this->db->get();
	}
}
