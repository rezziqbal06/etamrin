<?php
//api_admin
class A_Pengguna_Module_Model extends SENE_Model
{
    public $tbl 	= 'a_pengguna_module';
    public $tbl_as = 'apm';

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function trans_start()
    {
        $r = $this->db->autocommit(0);
        if ($r) {
            return $this->db->begin();
        }
        return false;
    }

    public function trans_commit()
    {
        return $this->db->commit();
    }

    public function trans_rollback()
    {
        return $this->db->rollback();
    }

    public function trans_end()
    {
        return $this->db->autocommit(1);
    }

    public function check_access($a_pengguna_id, $identifier)
    {
        $this->db->select_as("COUNT(*)", "jumlah", 0);
        $this->db->where("a_pengguna_id", $a_pengguna_id);
        $this->db->where("a_modules_identifier", $identifier);
        $d = $this->db->from($this->tbl)->get_first("object", 0);
        if (isset($d->jumlah)) {
            return $d->jumlah;
        }
        return 0;
    }

    public function pengguna_module($id)
    {
        $this->db->select();
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->where("a_pengguna_id", $id);
        $this->db->where("rule", "allowed_except", "AND", "!=");
        return $this->db->get("object", 0);
    }

    public function set($di)
    {
        if (!is_array($di)) {
            return 0;
        }
        $this->db->insert($this->tbl, $di, 0, 0);
        return $this->db->last_id;
    }

    public function update($id, $du, $filter='')
    {
        if (!is_array($du)) {
            return 0;
        }
        if (empty($filter)) {
            $this->db->where("id", $id);
        } else {
            foreach ($filter as $flt => $flt_val) {
                $this->db->where($flt, $flt_val);
            }
        }
        return $this->db->update($this->tbl, $du, 0);
    }

    public function del($id, $filter='')
    {
        if (empty($filter)) {
            $this->db->where("id", $id);
        } else {
            foreach ($filter as $flt => $flt_val) {
                $this->db->where($flt, $flt_val);
            }
        }
        return $this->db->delete($this->tbl);
    }

    public function updateModule($du, $pengguna_id)
    {
        if (!is_array($du)) {
            return 0;
        }
        $this->db->where("a_pengguna_id", $pengguna_id);
        $this->db->where("rule", "allowed_except", "AND", "!=");
        return $this->db->update($this->tbl, $du, 0);
    }
    public function getLastId()
    {
        $this->db->select_as("COALESCE(MAX($this->tbl_as.id),0)+1", "last_id", 0);
        $this->db->from($this->tbl, $this->tbl_as);
        $d = $this->db->get_first('', 0);
        if (isset($d->last_id)) {
            return $d->last_id;
        }
        return 0;
    }

    public function delModule($pengguna_id)
    {
        $this->db->where("a_pengguna_id", $pengguna_id);
        $this->db->where("tmp_active", "N");
        $this->db->where("rule", "allowed_except", "AND", "!=");
        return $this->db->delete($this->tbl);
    }
    public function getUserModules($a_pengguna_id)
    {
        $this->db->select_as("*, COALESCE(`a_modules_identifier`,'')", "module", 0);
        $this->db->from($this->tbl);
        $this->db->where("a_pengguna_id", $a_pengguna_id);
        $this->db->order_by("a_modules_identifier", "ASC");
        return $this->db->get();
    }
}
