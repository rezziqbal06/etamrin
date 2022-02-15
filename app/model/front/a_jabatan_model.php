<?php
class A_Jabatan_Model extends SENE_Model
{
    public $tbl = 'a_jabatan';
    public $tbl_as = 'aj';
    public $tbl_as2 = 'a_';
    public $tbl2 = 'a_jabatan_detail';
    public $tbl2_as = 'ajd';
    public function __construct()
    {
        parent::__construct();
        $this->db->setCharset("utf8mb4");
        $this->db->from($this->tbl, $this->tbl_as);
    }


    public function getByIds($ids = array())
    {
        $this->db->where_in("id", $ids);
        return $this->db->get();
    }

    public function getAll()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->where("is_active", 1);
        return $this->db->get();
    }

    public function getPendidikan()
    {
        $this->db->select_as("$this->tbl_as.min_pendidikan", 'nama', 0);
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->group_by("$this->tbl_as.min_pendidikan");
        return $this->db->get();
    }
    public function getById($id){
      $this->db->where('id',$id);
      return $this->db->get_first();
    }
}
