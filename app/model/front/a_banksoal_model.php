<?php
class A_BankSoal_Model extends JI_Model{
	var $tbl = 'a_banksoal';
	var $tbl_as = 'abs';
	var $tbl_as2 = 'bs';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}

	public function getAll($page=0,$pagesize=10,$sortCol="id",$sortDir="ASC",$keyword='',$is_active=''){
		$this->db->flushQuery();
		$this->db->select_as("$this->tbl_as.id",'id');
		$this->db->select_as("COALESCE($this->tbl_as.utype, '-')",'utype');
		$this->db->select_as("$this->tbl_as.nama",'nama');
		$this->db->select_as("$this->tbl_as.is_active",'is_active');
		$this->db->from($this->tbl,$this->tbl_as);
		if(strlen($is_active)) $this->db->where_as("$this->tbl_as.is_active",$this->db->esc($is_active),"AND","=");
		if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",1,0);
			$this->db->where_as("$this->tbl_as.id",$keyword,"OR","%like%",0,1);
    }
		$this->db->order_by($sortCol,$sortDir)->limit($page,$pagesize);
		return $this->db->get("object",0);
	}
	public function countAll($keyword='',$is_active=''){
		$this->db->flushQuery();
		$this->db->select_as("COUNT(DISTINCT $this->tbl_as.id)","jumlah",0);
		$this->db->from($this->tbl,$this->tbl_as);
		if(strlen($is_active)) $this->db->where_as("$this->tbl_as.is_active",$this->db->esc($is_active),"AND","=");
		if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",1,0);
			$this->db->where_as("$this->tbl_as.id",$keyword,"OR","%like%",0,1);
    }

		$d = $this->db->get_first("object",0);
		if(isset($d->jumlah)) return $d->jumlah;
		return 0;
	}

	public function getById($id){
		$this->db->where("id",$id);
		return $this->db->get_first();
	}
	public function getLastId(){
		$this->db->select_as("MAX($this->tbl_as.id)+1", "last_id", 0);
		$this->db->from($this->tbl, $this->tbl_as);
		$d = $this->db->get_first('',0);
		if(isset($d->last_id)) return $d->last_id;
		return 0;
	}
	public function get(){
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where("is_active",1);
		return $this->db->get();
	}
	public function count(){
		$this->db->select_as("COUNT(*)","total",0);
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where("is_active",1);
		$d = $this->db->get_first();
		if(isset($d->total)) return $d->total;
		return 0;
	}
	public function getByUtype($utype){
		$this->db->where('utype', $utype);
		$this->db->order_by('id','asc');
		return $this->db->get_first();
	}
}
