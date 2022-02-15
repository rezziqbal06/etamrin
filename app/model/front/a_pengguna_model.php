<?php
class A_Pengguna_Model extends Sene_Model {
	var $tbl = 'a_pengguna';
	var $tbl_as = 'ap';

	public function __construct(){
		parent::__construct();
		$this->db->from($this->tbl,$this->tbl_as);
	}
  public function getById($id){
    $this->db->where('id',$id);
    return $this->db->get_first();
  }
}
