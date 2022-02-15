<?php
class B_Soal_Model extends SENE_Model{
	var $tbl = 'b_soal';
	var $tbl_as = 'bs';
	var $tbl2 = 'a_banksoal';
	var $tbl2_as = 'abs';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}

	public function getAll($page=0,$pagesize=10,$sortCol="id",$sortDir="ASC",$keyword='',$a_banksoal_id='', $utype='',$is_active=''){
		$this->db->flushQuery();
		$this->db->select_as("$this->tbl_as.id","id");
		$this->db->select_as("$this->tbl_as.soal","soal");
		$this->db->select_as("$this->tbl_as.is_active","is_active");
		$this->db->from($this->tbl,$this->tbl_as);
		if(strlen($utype)) $this->db->where_as("$this->tbl_as.utype",$this->db->esc($utype),"AND","LIKE");
		if(strlen($is_active)) $this->db->where_as("$this->tbl_as.is_active",$this->db->esc($is_active),"AND","=");
		if(strlen($a_banksoal_id)) $this->db->where_as("$this->tbl_as.a_banksoal_id",$this->db->esc($a_banksoal_id),"OR","=",1,0);
		if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.soal",$keyword,"OR","%like%",1,1);
		}
		$this->db->order_by($sortCol,$sortDir)->limit($page,$pagesize);
		return $this->db->get("object",0);
	}
	public function countAll($keyword='', $a_banksoal_id='', $utype='', $is_active=''){
		$this->db->flushQuery();
		$this->db->select_as("COUNT(DISTINCT $this->tbl_as.id)","jumlah",0);
		$this->db->from($this->tbl,$this->tbl_as);
		if(strlen($utype)) $this->db->where_as("$this->tbl_as.utype",$this->db->esc($utype),"AND","LIKE");
		if(strlen($is_active)) $this->db->where_as("$this->tbl_as.is_active",$this->db->esc($is_active),"AND","=");
		if(strlen($a_banksoal_id)) $this->db->where_as("$this->tbl_as.a_banksoal_id",$this->db->esc($a_banksoal_id),"OR","=",1,0);
		if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.soal",$keyword,"OR","%like%",1,1);
		}
		$d = $this->db->get_first("object",0);
		if(isset($d->jumlah)) return $d->jumlah;
		return 0;
	}

	public function getAllVendor($page=0,$pagesize=10,$sortCol="id",$sortDir="ASC",$keyword='',$utype='',$badan_hukum=''){
		$this->db->flushQuery();
		$this->db->select_as("$this->tbl_as.id","id");
		$this->db->select_as("$this->tbl_as.badan_hukum","badan_hukum");
		$this->db->select_as("$this->tbl_as.kode","kode");
		$this->db->select_as("$this->tbl_as.nama","nama");
		$this->db->select_as("CONCAT($this->tbl_as.kabkota,', ',$this->tbl_as.negara)","alamat");
		$this->db->select_as("$this->tbl_as.telp","telp");
		$this->db->select_as("$this->tbl_as.is_active","is_active");
		$this->db->select_as("$this->tbl_as.is_vendor","is_vendor");
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->where_as("$this->tbl_as.is_vendor",$this->db->esc('1'),"AND","LIKE");
		if(strlen($utype)) $this->db->where_as("$this->tbl_as.utype",$this->db->esc($utype),"AND","LIKE");
		if(strlen($badan_hukum)) $this->db->where_as("$this->tbl_as.badan_hukum",$this->db->esc($badan_hukum),"AND","LIKE");
		if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",1,0);
			$this->db->where_as("$this->tbl_as.kode",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.kabkota",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.provinsi",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.negara",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.kodepos",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.alamat",$keyword,"OR","%like%",0,1);
    }
		$this->db->order_by($sortCol,$sortDir)->limit($page,$pagesize);
		return $this->db->get("object",0);
	}
	public function countAllVendor($keyword='',$utype='',$is_vendor='',$badan_hukum=''){
		$this->db->flushQuery();
		$this->db->select_as("COUNT(DISTINCT $this->tbl_as.id)","jumlah",0);
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->where_as("$this->tbl_as.is_vendor",$this->db->esc('1'),"AND","LIKE");
		if(strlen($utype)) $this->db->where_as("$this->tbl_as.utype",$this->db->esc($utype),"AND","LIKE");
		if(strlen($badan_hukum)) $this->db->where_as("$this->tbl_as.badan_hukum",$this->db->esc($badan_hukum),"AND","LIKE");
		if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.nama",$keyword,"OR","%like%",1,0);
			$this->db->where_as("$this->tbl_as.kode",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.kabkota",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.provinsi",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.negara",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.kodepos",$keyword,"OR","%like%",0,0);
			$this->db->where_as("$this->tbl_as.alamat",$keyword,"OR","%like%",0,1);
		}
		$d = $this->db->get_first("object",0);
		if(isset($d->jumlah)) return $d->jumlah;
		return 0;
	}

	public function getById($id){
		$this->db->where("id",$id);
		return $this->db->get_first();
	}
	public function set($di){
		if(!is_array($di)) return 0;
		return $this->db->insert($this->tbl,$di,0,0);
	}
	public function update($id,$du){
		if(!is_array($du)) return 0;
		$this->db->where("id",$id);
    return $this->db->update($this->tbl,$du,0);
	}
	public function del($id){
		$this->db->where("id",$id);
		return $this->db->delete($this->tbl);
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
	public function checkKode($kode){
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->where("kode",$kode);
		return $this->db->get_first();
	}
  public function getSearch($keyword=''){
    $this->db->select_as("$this->tbl_as.id","id",0);
    $this->db->select_as("$this->tbl_as.nama","text",0);
    $this->db->from($this->tbl,$this->tbl_as);
    if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.nama",($keyword),"OR","LIKE%%",1,0);
			$this->db->where_as("$this->tbl_as.alamat",($keyword),"OR","LIKE%%",0,0);
			$this->db->where_as("$this->tbl_as.kode",($keyword),"OR","LIKE%%",0,1);
		}
		$this->db->order_by("$this->tbl_as.a_company_id","desc");
		$this->db->order_by("$this->tbl_as.utype","desc");
    $this->db->order_by("$this->tbl_as.nama","asc");
    return $this->db->get('',0);
  }
  public function getParentSearch($keyword=''){
    $this->db->select_as("$this->tbl_as.id","id",0);
    $this->db->select_as("$this->tbl_as.nama","text",0);
    $this->db->from($this->tbl,$this->tbl_as);
		$this->db->where("$this->tbl_as.a_company_id","IS NULL");
    if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.nama",($keyword),"OR","LIKE%%",1,0);
			$this->db->where_as("$this->tbl_as.alamat",($keyword),"OR","LIKE%%",0,0);
			$this->db->where_as("$this->tbl_as.kode",($keyword),"OR","LIKE%%",0,1);
		}
		$this->db->order_by("$this->tbl_as.utype","desc");
    $this->db->order_by("$this->tbl_as.nama","asc");
    return $this->db->get('',0);
  }
	public function getSearchByIsVendor($is_vendor="0", $keyword=''){
		$this->db->select_as("$this->tbl_as.id", "id", 0);
		$this->db->select_as("$this->tbl_as.nama","text",0);
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->where_as("$this->tbl_as.is_vendor",$this->db->esc($is_vendor));
    if(strlen($keyword)>0){
			$this->db->where_as("$this->tbl_as.nama",($keyword),"OR","LIKE%%",1,0);
			$this->db->where_as("$this->tbl_as.alamat",($keyword),"OR","LIKE%%",0,0);
			$this->db->where_as("$this->tbl_as.kode",($keyword),"OR","LIKE%%",0,1);
		}
		$this->db->order_by("$this->tbl_as.utype","desc");
		$this->db->order_by("$this->tbl_as.nama","asc");
		return $this->db->get('',0);
	}
  public function getByBankSoalId($a_banksoal_id){
    $this->db->from($this->tbl,$this->tbl_as);
		$this->db->where("a_banksoal_id",$a_banksoal_id);
		$this->db->order_by("$this->tbl_as.urutan","asc");
    return $this->db->get('',0);
  }
}
