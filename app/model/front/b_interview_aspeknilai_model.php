<?php
class B_Interview_AspekNilai_Model extends JI_Model
{
  public $tbl = 'b_interview_aspeknilai';
  public $tbl_as = 'bian';
  public $tbl2 = 'a_leveljabatan';
  public $tbl2_as = 'alj';
  public $tbl3 = 'a_iteminterview';
  public $tbl3_as = 'aii';

  public function __construct()
  {
    parent::__construct();
    $this->db->setCharset("utf8mb4");
    $this->db->from($this->tbl, $this->tbl_as);
  }
  public function getById($id){
    $this->db->where('id',$id);
    return $this->db->get_first();
  }
  public function getByLevelJabatanId($id){
    $this->db->where('id',$id);
    return $this->db->get();
  }
  public function get($a_leveljabatan_id){
    $this->db->select_as("$this->tbl2_as.nama",'leveljabatan');
    $this->db->select_as("$this->tbl_as.passing_grade",'passing_grade');
    $this->db->select_as("$this->tbl3_as.*, $this->tbl_as.id",'id');
    $this->db->select_as("$this->tbl_as.a_leveljabatan_id",'a_leveljabatan_id');
    $this->db->select_as("$this->tbl_as.a_iteminterview_id",'a_iteminterview_id');
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'a_leveljabatan_id','');
    $this->db->join($this->tbl3, $this->tbl3_as, 'id', $this->tbl_as, 'a_iteminterview_id','');
    $this->db->where("$this->tbl_as.a_leveljabatan_id",$a_leveljabatan_id);
    return $this->db->get();
  }
}
