<?php
class A_Pertanyaan_Model extends JI_Model
{
  public $tbl = 'a_pertanyaan';
  public $tbl_as = 'ap';

  public function __construct()
  {
    parent::__construct();
    $this->db->from($this->tbl, $this->tbl_as);
  }
  public function getActive(){
    $this->db->order_by('urutan','asc')->where('is_active','1');
    return $this->db->get();
  }
}
