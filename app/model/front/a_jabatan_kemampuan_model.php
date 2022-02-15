<?php
class A_Jabatan_Kemampuan_Model extends SENE_Model
{
    public $tbl = 'a_jabatan_kemampuan';
    public $tbl_as = 'ajk';
    public $tbl2 = 'a_kemampuan';
    public $tbl2_as = 'ak';
    public function __construct()
    {
        parent::__construct();
        $this->db->setCharset("utf8mb4");
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function getById($id)
    {
        $this->db->where("id", $id);
        return $this->db->get_first();
    }
    public function getByJabatanId($id)
    {
        $this->db->select_as("$this->tbl_as.id", 'id', 0);
        $this->db->select_as("$this->tbl_as.a_kemampuan_id", 'a_kemampuan_id', 0);
        $this->db->select_as("COALESCE($this->tbl2_as.nama,'')", 'nama', 0);
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->join($this->tbl2, $this->tbl2_as, "id", $this->tbl_as, "a_kemampuan_id", 'LEFT');
        $this->db->where("a_jabatan_id", $id);
        return $this->db->get();
    }

    public function delByJabatanId($id)
    {
        $this->db->where("a_jabatan_id", $id);
        return $this->db->delete($this->tbl);
    }
}
