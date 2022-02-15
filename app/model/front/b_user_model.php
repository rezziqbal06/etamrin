<?php
class B_User_model extends SENE_Model
{
  public $tbl = 'b_user';
  public $tbl_as = 'bu';

  public function __construct()
  {
    parent::__construct();
    $this->db->from($this->tbl, $this->tbl_as);
  }

  public function getByApiWeb($api_web_token)
  {
    $this->db->where('api_web_token', $api_web_token);
    $this->db->from($this->tbl, $this->tbl_as);
    return $this->db->get_first('object', 0);
  }

  public function getByApiRegToken($api_reg_token)
  {
    $this->db->where('api_reg_token', $api_reg_token);
    $this->db->from($this->tbl, $this->tbl_as);
    return $this->db->get_first('object', 0);
  }

  public function getByApiRegTokenSHA($api_reg_token)
  {
    $this->db->where_as("SHA1(CONCAT($this->tbl_as.id,'-',$this->tbl_as.api_reg_token))", $this->db->esc($api_reg_token));
    $this->db->from($this->tbl, $this->tbl_as);
    return $this->db->get_first('object', 0);
  }

  public function update($id, $du)
  {
    if (!is_array($du)) {
      return 0;
    }
    $this->db->where("id", $id);
    return $this->db->update($this->tbl, $du, 0);
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
    return $this->db->get_first('',0);
  }
}
