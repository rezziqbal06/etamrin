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

  public function genKode()
  {
    $cdate = date('Y-m-d');
    $this->db->flushQuery();
    $this->db->select_as('CAST(SUBSTRING(kode,7) AS UNSIGNED)+1', 'urutan', 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->between("$this->tbl_as.cdate", "'$cdate 00:00:00'", "'$cdate 23:59:59'");
    $this->db->order_by('CAST(SUBSTRING(kode,7) AS UNSIGNED)', 'desc');
    $d = $this->db->get_first('object', 0);
    $r = date('ymd');
    if (isset($d->urutan)) {
      $r .= str_pad($d->urutan, 3, '0', STR_PAD_LEFT);
    } else {
      $r .= str_pad(1, 3, '0', STR_PAD_LEFT);
    }
    return $r;
  }

  public function auth($username)
  {
    $this->db->select("*");
    $this->db->select_as("COALESCE(`fb_id`,'-')", 'fb_id', 0);
    $this->db->select_as("COALESCE(`apple_id`,'-')", 'apple_id', 0);
    $this->db->select_as("COALESCE(`google_id`,'-')", 'google_id', 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.noktp"), "noktp", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.nosim"), "nosim", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.npwp"), "npwp", 0);
    $this->db->select_as("COALESCE(`api_web_token`,'-')", 'api_web_token', 0);
    $this->db->select_as("COALESCE(`status_kawin`,'')", 'status_kawin', 0);
    $this->db->select_as("COALESCE(`jml_anak`,'')", 'jml_anak', 0);
    $this->db->select_as("COALESCE(`saudara_ke`,'')", 'saudara_ke', 0);
    $this->db->select_as("COALESCE(`saudara_dari`,'')", 'saudara_dari', 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("`email`", $this->db->esc($username), "OR", "like", 1, 0);
    $this->db->where_as("`telp`", $this->db->esc($username), "OR", "like", 0, 1);
    return $this->db->get_first('object', 0);
  }

  public function checkToken($token, $kind = "api_web")
  {
    if (strlen($token) <= 4) {
      return false;
    }
    $dt = $this->db->where($kind . '_token', $token)->get();
    if (count($dt) > 1) {
      foreach ($dt as $d) {
        $this->setToken($d->id, "NULL", $kind);
      }
      return false;
    } elseif (count($dt) == 1) {
      return true;
    } else {
      return false;
    }
  }

  public function setToken($id, $token, $kind = "api_web")
  {
    $this->db->where("id", $id);
    $du = array(
      $kind . '_token' => $token,
      $kind . '_date' => date('Y-m-d')
    );
    return $this->db->update($this->tbl, $du);
  }

  public function getByToken($token, $kind = "api_web")
  {
    if (strlen($token) <= 4) {
      return new stdClass();
    }
    $this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", "id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.fb_id,'-')", "fb_id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.apple_id,'-')", "apple_id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.google_id,'-')", "google_id", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.noktp"), "noktp", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.nosim"), "nosim", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.npwp"), "npwp", 0);
    $this->db->from($this->tbl, $this->tbl_as);

    $this->db->where($kind . '_token', $token);
    return $this->db->get_first('object', 0);
  }

  public function setAgree($id)
  {
    $du = array('is_agree' => '1');
    return $this->db->where("id", $id)->update($this->tbl, $du);
  }

  public function register($di = array())
  {
    $this->db->flushQuery();
    if (isset($di['noktp'])) {
      $di['noktp'] = $this->__encrypt($di['noktp']);
    }
    if (isset($di['nosim'])) {
      $di['nosim'] = $this->__encrypt($di['nosim']);
    }
    if (isset($di['npwp'])) {
      $di['npwp'] = $this->__encrypt($di['npwp']);
    }
    return $this->db->insert($this->tbl, $di, 0, 0);
  }

  public function update($id, $du)
  {
    if (!is_array($du)) {
      return 0;
    }
    if (isset($du['noktp'])) {
      $du['noktp'] = $this->__encrypt($du['noktp']);
    }
    if (isset($du['nosim'])) {
      $du['nosim'] = $this->__encrypt($du['nosim']);
    }
    if (isset($du['npwp'])) {
      $du['npwp'] = $this->__encrypt($du['npwp']);
    }
    $this->db->where("id", $id);
    return $this->db->update($this->tbl, $du, 0);
  }

  public function getByEmail($email)
  {
    $this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", "id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.fb_id,'-')", "fb_id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.apple_id,'-')", "apple_id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.google_id,'-')", "google_id", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.noktp"), "noktp", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.nosim"), "nosim", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.npwp"), "npwp", 0);
    $this->db->from($this->tbl, $this->tbl_as);

    $this->db->where("email", $email);
    return $this->db->get_first();
  }

  public function getById($id)
  {
    $this->db->select_as("$this->tbl_as.*, $this->tbl_as.id", "id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.fb_id,'-')", "fb_id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.apple_id,'-')", "apple_id", 0);
    $this->db->select_as("COALESCE($this->tbl_as.google_id,'-')", "google_id", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.noktp"), "noktp", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.nosim"), "nosim", 0);
    $this->db->select_as($this->db->__decrypt("$this->tbl_as.npwp"), "npwp", 0);
    $this->db->from($this->tbl, $this->tbl_as);

    $this->db->where("id", $id);
    return $this->db->get_first();
  }

  public function getHasilTes($id)
  {
    $this->db->select_as("$this->tbl_as.hasil_tes", "hasil_tes", 0);
    $this->db->from($this->tbl, $this->tbl_as);

    $this->db->where("id", $id);
    $res = $this->db->get_first();
    if (isset($res->hasil_tes)) return $res->hasil_tes;
    return 0;
  }

  public function getByEmailAndSocialID($email, $social_id)
  {
    $this->db->where("email", $email);
    $this->db->where("fb_id", $social_id, 'or', 'like', 1, 0);
    $this->db->where("google_id", $social_id, 'or', 'like', 0, 1);
    $d = $this->db->get_first();
    if (isset($d->id)) {
      return $d;
    }
    return new stdClass();
  }

  public function getKode($a_company_inisial, $a_company_id = '', $fnama = '')
  {
    $a_company_inisial = strtoupper($a_company_inisial);
    $kode = $a_company_inisial;
    if (strlen($fnama) > 0) {
      $fnama = strtoupper($fnama);
      $kode = $a_company_inisial . '' . $fnama[0];
    }
    $this->db->flushQuery();
    $this->db->select_as('COUNT(*) total, CAST(COALESCE(SUBSTRING(kode,4),0) AS UNSIGNED)+1', 'urutan', 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where('kode', $kode, 'and', 'like%');
    $this->db->order_by('CAST(COALESCE(SUBSTRING(kode,4),0) AS UNSIGNED)', 'desc');
    if (strlen($a_company_id) > 0) {
      if (strtolower($a_company_id) == 'null') {
        $this->db->where_as('COALESCE(a_company_id,"-")', $this->db->esc('-'), 'and', '=');
      } else {
        $this->db->where('a_company_id', $a_company_id, 'and', '=');
      }
    }
    return $this->db->get_first('object', 0);
  }

  public function getKodeOnline($fnama_inisial)
  {
    $this->db->flushQuery();
    $this->db->select_as('CAST(SUBSTRING(kode,3) AS UNSIGNED)+1', 'urutan', 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where('kode', $fnama_inisial, 'and', 'like%');
    $this->db->order_by('CAST(SUBSTRING(kode,3) AS UNSIGNED)+1', 'desc');
    return $this->db->get_first('object', 0);
  }

  public function flushFcm($fcm_token = '')
  {
    if (strlen($fcm_token) > 50) {
      $sql = 'UPDATE `' . $this->tbl . '` SET fcm_token = "" WHERE fcm_token LIKE "' . $fcm_token . '"';
      $this->db->exec($sql);
    }
  }

  public function auth_sosmed($fb_id, $google_id, $apple_id, $email, $telp)
  {
    $this->db->select("*");
    $this->db->select_as("COALESCE(`api_web_token`,'-')", 'api_web_token', 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("$this->tbl_as.fb_id", $this->db->esc($fb_id), 'OR', 'LIKE', 1, 0);
    $this->db->where_as("$this->tbl_as.apple_id", $this->db->esc($apple_id), 'AND', 'LIKE', 0, 0);
    $this->db->where_as("$this->tbl_as.google_id", $this->db->esc($google_id), 'AND', 'LIKE', 0, 1);
    $this->db->where_as("$this->tbl_as.email", $this->db->esc($email), 'OR', 'LIKE', 1, 0);
    $this->db->where_as("$this->tbl_as.telp", $this->db->esc($telp), 'AND', 'LIKE', 0, 1);
    $this->db->order_by("id", "ASC");
    return $this->db->get_first('', 0);
  }

  public function checkEmail($email)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->select_as("COALESCE(apple_id,'NULL')", "apple_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("email", $this->db->esc($email), "AND", "LIKE");
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function checkNoKTP($noktp)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->select_as("COALESCE(apple_id,'NULL')", "apple_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as($this->__decrypt("noktp"), $this->db->esc($noktp), "AND", "LIKE");
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function checkNoSIM($nosim)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->select_as("COALESCE(apple_id,'NULL')", "apple_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as($this->__decrypt("nosim"), $this->db->esc($nosim), "AND", "LIKE");
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function checkTelp($telp)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("telp", $this->db->esc($telp), "AND", "LIKE");
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function checkEmailTelp($email, $telp)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("email", $this->db->esc($email), 'AND', 'LIKE', 1, 0);
    $this->db->where_as("telp", $this->db->esc($telp), 'AND', 'LIKE', 0, 1);
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function checkFBID($fb_id)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->select_as("COALESCE(apple_id,'NULL')", "apple_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("COALESCE(fb_id,'-')", $this->db->esc($fb_id), 'AND', 'LIKE', 0, 0);
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function checkAppleID($apple_id)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->select_as("COALESCE(apple_id,'NULL')", "apple_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("COALESCE(apple_id,'-')", $this->db->esc($apple_id), 'AND', 'LIKE', 0, 0);
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function checkGoogleID($google_id)
  {
    $this->db->select_as("*,COALESCE(google_id,'NULL')", "google_id", 0);
    $this->db->select_as("COALESCE(fb_id,'NULL')", "fb_id", 0);
    $this->db->select_as("COALESCE(apple_id,'NULL')", "apple_id", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("COALESCE(google_id,'-')", $this->db->esc($google_id), 'AND', 'LIKE', 0, 0);
    $this->db->order_by("id", "asc");
    return $this->db->get_first('', 0);
  }

  public function detail($id)
  {
    $this->db->select_as("$this->tbl_as.id", "id", 0);
    $this->db->select_as("$this->tbl_as.id", "b_user_id", 0);
    $this->db->select_as("$this->tbl_as.id", "b_user_id_seller", 0);
    $this->db->select_as("$this->tbl_as.fnama", "fnama", 0);
    $this->db->select_as("$this->tbl_as.image", "image", 0);
    $this->db->select_as("'0'", "rating", 0);
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where_as("$this->tbl_as.id", $this->db->esc($id), "AND", "=");
    return $this->db->get_first('', 0);
  }
  public function flushFcmToken($fcm_token_old)
  {
    $du = array("fcm_token" => '');
    $this->db->where("fcm_token", $fcm_token_old, 'AND', 'like%');
    return $this->db->update($this->tbl, $du, 0);
  }
  public function getByApiRegToken($api_reg_token)
  {
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where('api_reg_token', $api_reg_token);
    return $this->db->get_first('object', 0);
  }
  public function getByApiWeb($api_web_token)
  {
    $this->db->from($this->tbl, $this->tbl_as);
    $this->db->where('api_web_token', $api_web_token);
    return $this->db->get_first('object', 0);
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
}
