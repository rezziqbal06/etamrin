<?php
class C_Interview_Nilai_Model extends JI_Model
{
  public $tbl = 'c_interview_nilai';
  public $tbl_as = 'cin';

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
  public function getByInterviewId($c_interview_id){
    $this->db->where('c_interview_id',$c_interview_id);
    return $this->db->get();
  }
  public function getByInterviewIdPenggunaIdRole($c_interview_id,$a_pengguna_id,$role){
    $this->db->where('c_interview_id',$c_interview_id);
    $this->db->where('a_pengguna_id',$a_pengguna_id);
    $this->db->where('role',$role);
    return $this->db->get();
  }
  public function delByInterviewIdPenggunaIdRole($c_interview_id,$a_pengguna_id,$role){
    $this->db->where('c_interview_id',$c_interview_id);
    $this->db->where('a_pengguna_id',$a_pengguna_id);
    $this->db->where('role',$role);
    return $this->db->delete($this->tbl);
  }
}
