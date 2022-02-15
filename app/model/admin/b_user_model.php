<?php
class B_User_Model extends JI_Model
{
  public $tbl = 'b_user';
  public $tbl_as = 'bu';

  public function __construct()
  {
    parent::__construct();
    $this->db->from($this->tbl, $this->tbl_as);
  }

  public function getById($id)
  {
    $this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", "id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.fb_id,'-')", "fb_id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.google_id,'-')", "google_id", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.noktp"), "noktp", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.nosim"), "nosim", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.npwp"), "npwp", 0);
    $this->db->from($this->tbl, $this->tbl_as);

    $this->db->where("id", $id);
    return $this->db->get_first();
  }
}
