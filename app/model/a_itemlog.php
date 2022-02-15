<?php
class A_ItemLog_Model extends JI_Model
{
	public $tbl = 'a_itemlog';
	public $tbl_as = 'ail';

	public function __construct()
	{
		parent::__construct();
		$this->db->from($this->tbl, $this->tbl_as);
	}
	public function get()
	{
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->order_by('utype', 'ASC')->order_by('urutan','asc');
		return $this->db->get('',0);
	}
  public function getById($id){
    $this->db->where('id',$id);
    return $this->db->get_first('',0);
  }
}
