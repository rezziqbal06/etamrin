<?php
class B_User_Keluarga_Model extends JI_Model
{
  public $tbl = 'b_user_keluarga';
  public $tbl_as = 'buk';
  public $tbl2 = 'b_user';
  public $tbl2_as = 'bu';

  public function __construct()
  {
    parent::__construct();
    $this->db->from($this->tbl, $this->tbl_as);
  }
  public function getByUserId($b_user_id)
  {
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where("b_user_id", $b_user_id);
    return $this->db->get();
  }
  public function getByIdUserId($id,$b_user_id)
  {
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where("id", $id);
    $this->db->where("b_user_id", $b_user_id);
    return $this->db->get_first();
  }
  public function getById($id)
  {
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where("id", $id);
    return $this->db->get_first();
  }
}
