<?php
class A_LevelJabatan_Model extends JI_Model
{
  public $tbl = 'a_leveljabatan';
  public $tbl_as = 'alj';

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
}
