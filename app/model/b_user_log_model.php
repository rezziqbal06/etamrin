<?php
class B_User_Log_Model extends JI_Model
{
	public $tbl = 'b_user_log';
	public $tbl_as = 'bul';
	public $tbl2 = 'a_itemlog';
	public $tbl2_as = 'ail';

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
  public function getByUserId($b_user_id){
		$this->db->select_as("$this->tbl_as.id",'a_itemlog_id');
		$this->db->select_as("$this->tbl2_as.nama",'itemlog');
		$this->db->select_as("$this->tbl2_as.is_keterangan",'is_keterangan');
		$this->db->select_as("$this->tbl_as.cdate",'cdate');
		$this->db->select_as("$this->tbl_as.keterangan",'keterangan');
		$this->db->from($this->tbl, $this->tbl_as);
		$this->db->join($this->tbl2, $this->tbl2_as, 'id', $this->tbl_as, 'a_itemlog_id');
		$this->db->where_as("$this->tbl_as.b_user_id", $this->db->esc($b_user_id));
    $this->db->order_by("$this->tbl_as.cdate",'desc');
    return $this->db->get('',0);
  }
}
