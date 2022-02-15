<?php
class A_CMS_Model extends JI_Model
{
	public $tbl = 'a_cms';
	public $tbl_as = 'cms';

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
}
