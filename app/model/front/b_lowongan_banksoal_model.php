<?php
class B_Lowongan_BankSoal_Model extends JI_Model{
	var $tbl = 'b_lowongan_banksoal';
	var $tbl_as = 'blbs';
	var $tbl_as2 = 'blbs2';
	var $tbl2 = 'a_banksoal';
	var $tbl2_as = 'abs';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}
	public function getByLowonganId($id){
		$this->db->select_as("$this->tbl_as.id",'id');
		$this->db->select_as("$this->tbl_as.b_lowongan_id",'b_lowongan_id');
		$this->db->select_as("$this->tbl_as.a_banksoal_id",'a_banksoal_id');
		$this->db->select_as("$this->tbl_as.urutan",'urutan');
		$this->db->select_as("COALESCE($this->tbl2_as.utype, '-')",'utype');
		$this->db->select_as("$this->tbl2_as.nama",'nama');
		$this->db->select_as("$this->tbl2_as.ket",'ket');
		$this->db->select_as("$this->tbl_as.passing_grade",'passing_grade');
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->join($this->tbl2,$this->tbl2_as,'id',$this->tbl_as,'a_banksoal_id','');
		$this->db->where_as("$this->tbl2_as.is_active",$this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.b_lowongan_id",$this->db->esc($id));
		$this->db->order_by("$this->tbl_as.urutan",'asc');
		return $this->db->get('',0);
	}
	public function getByIdLowonganId($id,$b_lowongan_id){
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->join($this->tbl2,$this->tbl2_as,'id',$this->tbl_as,'a_banksoal_id','');
		$this->db->where_as("$this->tbl2_as.is_active",$this->db->esc('1'));
		$this->db->where_as("$this->tbl_as.id",$this->db->esc($id));
		$this->db->where_as("$this->tbl_as.b_lowongan_id",$this->db->esc($b_lowongan_id));
		$this->db->order_by("$this->tbl_as.urutan",'asc');
		return $this->db->get_first('',0);
	}

}
