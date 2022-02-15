<?php
class C_Apply_SessionTes_Model extends JI_Model
{
  public $tbl = 'c_apply_sessiontes';
  public $tbl_as = 'casst';
  public $tbl2 = 'a_banksoal';
  public $tbl2_as = 'abs';

  public function __construct()
  {
    parent::__construct();
    $this->db->from($this->tbl, $this->tbl_as);
  }
  public function check($a_banksoal_id,$b_user_id,$c_apply_id,$utype=''){
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where("a_banksoal_id", $a_banksoal_id);
    $this->db->where("b_user_id", $b_user_id);
    $this->db->where("c_apply_id", $c_apply_id);
    if(strlen($utype)) $this->db->where("utype", $utype);
    return $this->db->get_first();
  }
  public function getCurrent($b_user_id,$c_apply_id){
    $this->db->select_as("$this->tbl_as.*, $this->tbl_as.id",'id');
    $this->db->select_as("$this->tbl2_as.nama",'nama');
    $this->db->select_as("$this->tbl2_as.utype",'utype');
    $this->db->select_as("$this->tbl2_as.ket",'ket');
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->join($this->tbl2, $this->tbl2_as,'id', $this->tbl_as,'a_banksoal_id','');
    $this->db->where_as("$this->tbl_as.b_user_id", $this->db->esc($b_user_id));
    $this->db->where_as("$this->tbl_as.c_apply_id", $this->db->esc($c_apply_id));
    return $this->db->get();
  }
  public function sudahTesCS($c_apply_id){
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("$this->tbl_as.c_apply_id", $this->db->esc($c_apply_id));
    $this->db->where_as("$this->tbl_as.utype", $this->db->esc('cs'));
    $this->db->where_as("$this->tbl_as.is_done", $this->db->esc('1'));
    $this->db->order_by('id','desc');
    return $this->db->get_first();
  }
}
