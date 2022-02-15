<?php
class A_Referrer_Model extends SENE_Model{
	public $tbl = 'a_referrer';
	public $tbl_as = 'ar';

  public function __construct()
  {
    parent::__construct();
    $this->db->from($this->tbl, $this->tbl_as);
  }

	public function search($referrer){
		$this->db->where("code_referrer",$referrer,'AND','like%%');
		return $this->db->from($this->tbl)->get_first();
	}
}
