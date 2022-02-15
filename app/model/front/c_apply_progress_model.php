<?php
class C_Apply_Progress_Model extends JI_Model
{
  public $tbl = 'c_apply_progress';
  public $tbl_as = 'cap';
  public $tbl2 = 'b_user';
  public $tbl2_as = 'bu';

  public function __construct()
  {
    parent::__construct();
    $this->db->from($this->tbl, $this->tbl_as);
  }

  public function getByApplyid($c_apply_id){
    $this->db->where('c_apply_id',$c_apply_id);
    return $this->db->get();
  }

  public function getCurrent($b_user_id,$c_apply_id,$utype='step')
  {
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where("b_user_id", $b_user_id);
    $this->db->where("c_apply_id", $c_apply_id);
    if(strlen($utype)) $this->db->where_as("utype", $this->db->esc($utype));
    return $this->db->get();
  }

  public function check($b_user_id,$c_apply_id,$stepkey,$utype='step')
  {
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("b_user_id", $this->db->esc($b_user_id));
    $this->db->where("c_apply_id", $c_apply_id);
    $this->db->where_as("stepkey", $this->db->esc($stepkey));
    if(strlen($utype)) $this->db->where_as("utype", $this->db->esc($utype));
    return $this->db->get_first('', 0);
  }
}
