<?php
class A_ItemInterview_Model extends JI_Model
{
	public $tbl = 'a_iteminterview';
	public $tbl_as = 'aii';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}
  public function get(){
    $this->db->order_by('urutan','asc');
    return $this->db->get();
  }
}
