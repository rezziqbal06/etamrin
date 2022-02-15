<?php
class A_Pengguna_Model extends Sene_Model {
	var $tbl = 'a_pengguna';
	var $tbl_as = 'ap';
	var $tbl3 = 'c_produk';
	var $tbl3_as = 'cp';
	var $tbl4 = 'd_order';
	var $tbl4_as = 'dor';
	var $tbl5 = 'd_order_detail';
	var $tbl5_as = 'dod';
	var $tbl6 = 'b_kategori';
	var $tbl6_as = 'bk';
	var $tbl7 = 'd_redeem_produk';
	var $tbl7_as = 'drp';
	var $tbl8 = 'd_redeem';
	var $tbl8_as = 'dr';

	var $tbl9 = 'a_company';
	var $tbl9_as = 'ac';
	var $tbl10 = 'a_jabatan';
	var $tbl10_as = 'aj';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}
	public function auth($username){
		$this->db
			->select("*")
			->where_as("username",$this->db->esc($username));
		return $this->db->get_first('object',0); //
	}
	public function checkToken($kind="api_web",$token){
		$dt = $this->db->where($kind.'_token',$token)->get();
		if(count($dt)>1){
			foreach($dt as $d){
				$this->setToken($kind,"NULL",$d->id);
			}
			return false;
		}else if(count($dt)==1){
			return true;
		}else{
			return false;
		}
	}
	public function setToken($kind="api_web",$token,$id){
		$du = array($kind.'_token'=>$token);
		return $this->db->where("id",$id)->update($this->tbl,$du);
	}
	public function setAgree($id){
		$du = array('is_agree'=>'1');
		return $this->db->where("id",$id)->update($this->tbl,$du);
	}
	public function getById($id){
		$this->db->where('id',$id);
		return $this->db->get_first();
	}
	public function getByCompanyId($company_id, $mindate, $maxdate){
		$this->db->select_as("$this->tbl9_as.nama",'penempatan',0);
		$this->db->select_as("$this->tbl10_as.nama",'jabatan',0);
		$this->db->select_as("$this->tbl_as.nip",'nip',0);
		$this->db->select_as("$this->tbl_as.nama",'nama',0);
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->join($this->tbl9, $this->tbl9_as, 'id', $this->tbl_as, 'a_company_id', 'left');
		$this->db->join($this->tbl10, $this->tbl10_as, 'id', $this->tbl_as, 'a_jabatan_id', 'left');
		if(strlen($company_id) > 0) $this->db->where_as("$this->tbl_as.a_company_id", $this->db->esc($company_id));

		if (strlen($mindate)== 10 && strlen($maxdate) == 10) {
				$this->db->between("DATE($this->tbl_as.cdate)", 'DATE("'.$mindate.'")', 'DATE("'.$maxdate.'")');
		} elseif (strlen($mindate)!= 10 && strlen($maxdate) == 10) {
				$this->db->where_as("DATE($this->tbl_as.cdate)", 'DATE("'.$maxdate.'")', "AND", '<=');
		} elseif (strlen($mindate)== 10 && strlen($maxdate) != 10) {
				$this->db->where_as("DATE($this->tbl_as.cdate)", 'DATE("'.$mindate.'")', "AND", '>=');
		}
		return $this->db->get('',0);
	}
	public function update($id,$du){
		$this->db->where('id',$id);
		return $this->db->update($this->tbl,$du);
	}
	public function getTerapisByCompanyId($a_company_id){
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->where_as("$this->tbl_as.a_company_id",$a_company_id);
		$this->db->where_in("a_jabatan_nama",array('Terapis','Dokter','Perawat'));
		return $this->db->get();
	}
	public function getDokterByCabang($a_company_id){
		$this->db->where_as("LOWER(a_jabatan_nama)",$this->db->esc('dokter'));
		$this->db->where("a_company_id",$a_company_id);
		return $this->db->get();
	}

	public function getTerapisByCabang($a_company_id){
		$this->db->from($this->tbl,$this->tbl_as);
    $this->db->where_as("$this->tbl_as.is_active",1);
		$this->db->where_as("$this->tbl_as.a_company_id",$a_company_id);
		$this->db->where_in("a_jabatan_nama",array('Terapis','Dokter','Perawat'));
		return $this->db->get();
	}

	public function getTerapisPoinRedeem($a_pengguna_id, $beli_mindate, $beli_maxdate){
		$this->db->select_as("$this->tbl7_as.a_pengguna_id_terapis",'a_pengguna_id_terapis',0);
		$this->db->select_as("$this->tbl3_as.nama",'produk',0);
		$this->db->select_as("$this->tbl3_as.poin_terapis",'poin_terapis',0);
		$this->db->select_as("COUNT(*)",'total_tindakan',0);
		$this->db->select_as("((cp.poin_terapis) * COUNT(*))",'total_poin',0);
		$this->db->from($this->tbl7,$this->tbl7_as);
		$this->db->between("DATE($this->tbl8_as.cdate)","DATE('$beli_mindate')","DATE('$beli_maxdate')");
		$this->db->join($this->tbl3,$this->tbl3_as,"id",$this->tbl7_as,'c_produk_id','');
		$this->db->join($this->tbl8,$this->tbl8_as,"id",$this->tbl7_as,'d_redeem_id','');
		$this->db->join($this->tbl4,$this->tbl4_as,"id",$this->tbl8_as,'d_order_id','');
    $this->db->where_as("COALESCE($this->tbl7_as.a_pengguna_id_terapis,'0')",$a_pengguna_id);
		$this->db->where_as("COALESCE($this->tbl4_as.utype,'0')",$this->db->esc("order_selesai"));
		$this->db->group_by("$this->tbl7_as.c_produk_id");
		return $this->db->get('',0);
	}

	public function getTerapisPoinOrder($a_pengguna_id, $beli_mindate, $beli_maxdate){
		$this->db->select_as("$this->tbl5_as.a_pengguna_id_terapis",'a_pengguna_id_terapis',0);
		$this->db->select_as("$this->tbl3_as.nama",'produk',0);
		$this->db->select_as("$this->tbl3_as.utype",'c_produk_utype',0);
		$this->db->select_as("$this->tbl3_as.poin_terapis",'poin_terapis',0);
		$this->db->select_as("SUM($this->tbl5_as.qty)",'total_tindakan',0);
		$this->db->select_as("(($this->tbl3_as.poin_terapis) * SUM($this->tbl5_as.qty))",'total_poin',0);
		$this->db->from($this->tbl5,$this->tbl5_as);
		$this->db->join($this->tbl4,$this->tbl4_as,"id",$this->tbl5_as,'d_order_id','');
		$this->db->join($this->tbl3,$this->tbl3_as,"id",$this->tbl5_as,'c_produk_id','');
		$this->db->where_as("COALESCE($this->tbl5_as.a_pengguna_id_terapis,'0')",$a_pengguna_id);
    $this->db->where_as("COALESCE($this->tbl4_as.utype,'0')",$this->db->esc("order_selesai"));
		$this->db->between("DATE(COALESCE($this->tbl5_as.sdate,$this->tbl4_as.date_order))","DATE('$beli_mindate')","DATE('$beli_maxdate')");
    $this->db->group_by("CONCAT($this->tbl5_as.c_produk_id,'-',$this->tbl5_as.a_pengguna_id_terapis)");
    return $this->db->get('',0);
	}

	public function getAsistensiPoinRedeem($a_pengguna_id, $beli_mindate, $beli_maxdate){
		$this->db->select_as("$this->tbl7_as.a_pengguna_id_asistensi",'a_pengguna_id_asistensi',0);
		$this->db->select_as("$this->tbl3_as.nama",'produk',0);
		$this->db->select_as("$this->tbl3_as.poin_asistensi",'poin_asistensi',0);
		$this->db->select_as("COUNT(*)",'total_tindakan',0);
		$this->db->select_as("((cp.poin_asistensi) * COUNT(*))",'total_poin',0);
		$this->db->from($this->tbl7,$this->tbl7_as);
		$this->db->between("DATE($this->tbl8_as.cdate)","DATE('$beli_mindate')","DATE('$beli_maxdate')");
		$this->db->join($this->tbl3,$this->tbl3_as,"id",$this->tbl7_as,'c_produk_id','');
		$this->db->join($this->tbl8,$this->tbl8_as,"id",$this->tbl7_as,'d_redeem_id','');
		$this->db->join($this->tbl4,$this->tbl4_as,"id",$this->tbl8_as,'d_order_id','');
    $this->db->where_as("COALESCE($this->tbl7_as.a_pengguna_id_asistensi,'0')",$a_pengguna_id);
		$this->db->where_as("COALESCE($this->tbl4_as.utype,'0')",$this->db->esc("order_selesai"));
		$this->db->group_by("$this->tbl7_as.c_produk_id");
		return $this->db->get('',0);
	}

	public function getAsistensiPoinOrder($a_pengguna_id, $beli_mindate, $beli_maxdate){
		$this->db->select_as("$this->tbl5_as.a_pengguna_id_asistensi",'a_pengguna_id_asistensi',0);
		$this->db->select_as("$this->tbl3_as.nama",'produk',0);
		$this->db->select_as("$this->tbl3_as.poin_asistensi",'poin_asistensi',0);
		$this->db->select_as("SUM($this->tbl5_as.qty)",'total_tindakan',0);
		$this->db->select_as("((cp.poin_asistensi) * SUM($this->tbl5_as.qty))",'total_poin',0);
		$this->db->from($this->tbl5,$this->tbl5_as);
		$this->db->join($this->tbl3,$this->tbl3_as,"id",$this->tbl5_as,'c_produk_id','');
		$this->db->join($this->tbl4,$this->tbl4_as,"id",$this->tbl5_as,'d_order_id','');
		$this->db->between("DATE(COALESCE($this->tbl5_as.sdate,$this->tbl4_as.date_order))","DATE('$beli_mindate')","DATE('$beli_maxdate')");
    $this->db->where_as("COALESCE($this->tbl5_as.a_pengguna_id_asistensi,'0')",$a_pengguna_id);
    $this->db->where_as("COALESCE($this->tbl4_as.utype,'0')",$this->db->esc("order_selesai"));
		$this->db->group_by("$this->tbl5_as.c_produk_id");
		return $this->db->get('',0);
	}
	public function downloadXLS($a_company_id=''){
		$this->db->select_as("$this->tbl_as.id",'id',0);
		$this->db->select_as("$this->tbl_as.nama",'nama',0);
		$this->db->select_as("$this->tbl_as.nip",'nip',0);
		$this->db->select_as("COALESCE($this->tbl9_as.nama,'-')",'penempatan',0);
		$this->db->select_as("COALESCE($this->tbl10_as.nama,'-')",'jabatan',0);
		$this->db->from($this->tbl,$this->tbl_as);
		$this->db->join($this->tbl9,$this->tbl9_as,"id",$this->tbl_as,'a_company_id','');
		$this->db->join($this->tbl10,$this->tbl10_as,"id",$this->tbl_as,'a_jabatan_id','');
		$this->db->order_by("$this->tbl_as.nama","ASC");
		$this->db->where_as("$this->tbl_as.is_active",$this->db->esc("1"));
		if(strlen($a_company_id)) $this->db->where_as("$this->tbl_as.a_company_id",$this->db->esc($a_company_id));
		return $this->db->get('',0);
	}
	public function getKaryawanAktif(){
		$this->db->where('is_active',1);
		$this->db->order_by('nama','asc');
		return $this->db->get();
	}
	public function check($a_jabatan_id,$email){
		$this->db->where('a_jabatan_id',$a_jabatan_id);
		$this->db->where('email',$email);
		return $this->db->get_first();
	}
	public function set($di){
		if(!is_array($di)) return 0;
		return $this->db->insert($this->tbl,$di,0,0);
	}
}
