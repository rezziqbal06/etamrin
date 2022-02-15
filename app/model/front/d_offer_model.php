<?php
class D_Offer_Model extends JI_Model
{
  public $tbl = 'd_offer';
  public $tbl_as = 'do';

  public function __construct()
  {
    parent::__construct();
    $this->db->setCharset("utf8mb4");
    $this->db->from($this->tbl, $this->tbl_as);
  }

  public function getById($id)
  {
    $this->db->where("id", $id);
    return $this->db->get_first();
  }

  public function getByApplyId($id)
  {
    $this->db->where("c_apply_id", $id);
    return $this->db->get_first();
  }
}
