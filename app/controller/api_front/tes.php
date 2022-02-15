<?php
class Tes extends JI_Controller
{
  public $max_file_size = 2000000;

  public function __construct()
  {
    parent::__construct();
    $this->load('api_front/a_jabatan_model', 'ajm');
    $this->load('api_front/a_banksoal_model', 'absm');
    $this->load('api_front/b_lowongan_model', 'blm');
    $this->load('api_front/b_user_model', 'bum');
    $this->load('api_front/c_apply_capturetes_model', 'cactm');
    $this->load('api_front/c_apply_model', 'cam');
    $this->load('api_front/c_apply_sessiontes_model', 'castm');
    $this->load('api_front/c_apply_tes_model', 'catm');
  }
  public function index()
  {
  }
  public function mulai($utype = '')
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 401;
      $this->message = 'Harus login, silakan login / daftar dulu';
      $this->__json_out($data);
      return;
    }

    $cam = $this->cam->getByUserId($d['sess']->user->id);
    if (!isset($cam->id)) {
      $this->status = 1249;
      $this->message = 'Maaf anda belum apply, silakan apply lowongan terlebih dahulu';
      $this->__json_out($data);
      return;
    }

    if (!isset($d['sess']->a_banksoal_id)) {
      $this->status = 1210;
      $this->message = 'Invalid Bank Soal ID';
      $this->__json_out($data);
      return;
    }

    if (!isset($d['sess']->soal)) {
      $this->status = 1211;
      $this->message = 'Undefined List Soal';
      $this->__json_out($data);
      return;
    }

    if (!is_array($d['sess']->soal)) {
      $this->status = 1212;
      $this->message = 'Invalid List Soal type';
      $this->__json_out($data);
      return;
    }

    if (count($d['sess']->soal) == 0) {
      $this->status = 1213;
      $this->message = 'Empty soal';
      $this->__json_out($data);
      return;
    }

    $a_banksoal_id = isset($d['sess']->a_banksoal_id) ? $d['sess']->a_banksoal_id : 'NULL';
    $waktu = isset($d['sess']->waktu) ? (int) $d['sess']->waktu : 0;
    $di = array();
    $di['ldate'] = 'NOW()';
    $di['a_banksoal_id'] = $a_banksoal_id;
    $di['b_user_id'] = $d['sess']->user->id;
    $di['c_apply_id'] = $cam->id;
    if(isset($this->config->semevar->tes_auto_done) && !empty($this->config->semevar->tes_auto_done)){
      $di['is_done'] = '1';
    }
    $castm = $this->castm->check($a_banksoal_id, $d['sess']->user->id, $cam->id, $utype);
    if (!isset($castm->id)) {
      $di['a_banksoal_id'] = $a_banksoal_id;
      $di['utype'] = $utype;
      $di['cdate'] = 'NOW()';
      $di['time_spent'] = '0';
      $this->castm->set($di);
    } else {
      if (!empty($castm->is_expired)) {
        $this->status = 1200;
        $this->message = 'Maaf anda telah melewati batas waktu ujian';
        $this->__json_out($data);
        return;
      }
      if (!empty($castm->is_done)) {
        $this->status = 1204;
        $this->message = 'Maaf Anda telah menyelesaikan tes ini';
        $this->__json_out($data);
        return;
      }
      $this->castm->update($castm->id, $di);
    }
    $cactm = $this->cactm->getCurrent($a_banksoal_id,$d['sess']->user->id, $cam->id, $utype);
    if (count($cactm) && is_array($cactm)) {
      foreach ($cactm as $buc) {
        if (file_exists(SEMEROOT . $buc->src) && is_file(SEMEROOT . $buc->src)) {
          unlink(SEMEROOT . $buc->src);
        }
      }
      $this->cactm->delCurrent($a_banksoal_id,$d['sess']->user->id, $cam->id, $utype);
    }

    $this->status = 200;
    $this->message = 'Berhasil';
    $data = new stdClass();
    $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    if($waktu>0){
      $data->time_start = $dateTime->modify("+$waktu minutes")->format("M j, Y H:i:s");
    }else{
      if($utype == 'cs' || $utype == 'kepribadian'){
        $data->time_start = $dateTime->modify("+10 minutes")->format("M j, Y H:i:s");
      }else{
        $data->time_start = $dateTime->modify("+30 minutes")->format("M j, Y H:i:s");
      }
    }
    if($utype != 'cs'){
      $this->bum->update($d['sess']->user->id, array('apply_statno' => '4', 'is_edit_disabled' => '1'));
      if($utype == 'iq'){
        $this->_setULog($d['sess']->user->id, 17);
      }else{
        $this->_setULog($d['sess']->user->id, 21);
      }
    }else{
      $this->_setULog($d['sess']->user->id, 9);
    }
    $this->__json_out($data);
  }
  public function capture($utype)
  {
    $d = $this->__init();
    $data = array();
    $data['failed'] = array();

    if (!isset($d['sess']->user->token)) {
      $this->status = 401;
      $this->message = 'Unauthorized access! Please don\'t try any more :> --drosanda';
      $this->__json_out($data);
      die();
    }
    $cam = $this->cam->getByUserId($d['sess']->user->id);
    if (!isset($cam->id)) {
      $this->status = 1229;
      $this->message = 'Maaf anda belum apply atau proses seleksi anda sudah selesai.';
      $this->__json_out($data);
      return;
    }

    if(!isset($d['sess']->a_banksoal_id)){
      $this->status = 1228;
      $this->message = 'ID Bank Soal tidak valid';
      $this->__json_out($data);
      return;
    }
    $a_banksoal_id = (int) $d['sess']->a_banksoal_id;

    $utype = strtolower(trim($utype));

    //check apikey
    $apikey = $this->input->get('apikey');
    if ($d['sess']->user->token != $apikey) {
      $this->status = 402;
      $this->message = 'Missing or invalid API key';
      $this->__json_out($data);
      die();
    }

    //create target directory
    $targetdir = $this->config->semevar->media_capture;
    if (empty(realpath(SEMEROOT . $targetdir))) {
      if (PHP_OS == "WINNT") {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir);
      } else {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir, 0775);
      }
    }

    $targetdir = $targetdir . DIRECTORY_SEPARATOR . date("Y");
    if (empty(realpath(SEMEROOT . $targetdir))) {
      if (PHP_OS == "WINNT") {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir);
      } else {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir, 0775);
      }
    }

    $targetdir = $targetdir . DIRECTORY_SEPARATOR . date("m");
    if (empty(realpath(SEMEROOT . $targetdir))) {
      if (PHP_OS == "WINNT") {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir);
      } else {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir, 0775);
      }
    }

    $targetdir = $targetdir . DIRECTORY_SEPARATOR . $d['sess']->user->id;
    if (empty(realpath(SEMEROOT . $targetdir))) {
      if (PHP_OS == "WINNT") {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir);
      } else {
        if (!is_dir(SEMEROOT . $targetdir)) mkdir(SEMEROOT . $targetdir, 0775);
      }
    }

    if (is_array($_FILES) && count($_FILES)>0) {
      foreach ($_FILES as $keyname => $v) {
        //check extension
        $filenames = pathinfo($v['name']);
        if (isset($filenames['extension'])) {
          $fileext = strtolower($filenames['extension']);
        } else {
          $data['failed'][] = 'No extension can be found';
          continue;
        }

        unset($filenames);
        if (!in_array($fileext, array("jpg", "jpeg"))) {
          $data['failed'][] = 'Invalid extension are uploaded';
          continue;
        }

        //check filesize
        $v['size'] = (int) $v['size'];
        if ($v['size'] > $this->max_file_size) {
          $data['failed'][] = 'Ukuran file terlalu besar, pastikan kurang dari ' . ceil($this->max_file_size / 1024000) . ' MB';
          continue;
        }

        $v['size'] = (int) $v['size'];
        if ($v['size'] <= 1000) {
          $this->status = 1221;
          $this->message = 'Tidak diberi akses kamera';
          $data['failed'][] = 'Ukuran gambar terlalu kecil ' . ceil($this->max_file_size / 1024000) . ' MB';
          continue;
        }


        //open transaction
        $this->cactm->trans_start();
        $last_id = $this->cactm->getLastId($a_banksoal_id, $d['sess']->user->id, $cam->id, $utype);

        //generate filename
        $filename = strtolower($utype) . '-cap-' . str_pad($last_id, 5, '0', STR_PAD_LEFT);
        $is_found = file_exists(SEMEROOT . $targetdir . DIRECTORY_SEPARATOR . $filename . '.' . $fileext);
        while ($is_found) {
          $last_id++;
          $filename = strtolower($utype) . '-cap-' . str_pad($last_id, 5, '0', STR_PAD_LEFT);
          $is_found = file_exists(SEMEROOT . $targetdir . DIRECTORY_SEPARATOR . $filename . '.' . $fileext);
        }
        $filename .= '.' . $fileext;

        move_uploaded_file($v['tmp_name'], SEMEROOT . $targetdir . DIRECTORY_SEPARATOR . $filename);
        if (is_file(SEMEROOT . $targetdir . DIRECTORY_SEPARATOR . $filename) && file_exists(SEMEROOT . $targetdir . DIRECTORY_SEPARATOR . $filename)) {
          $di = array(
            'a_banksoal_id' => $d['sess']->a_banksoal_id,
            'b_user_id' => $d['sess']->user->id,
            'c_apply_id' => $cam->id,
            'utype' => $utype,
            'cdate' => 'NOW()',
            'src' => $targetdir . DIRECTORY_SEPARATOR . $filename
          );
          $res = $this->cactm->set($di);
          if ($res) {
            $this->status = 200;
            $this->message = 'Berhasil';
            $this->cactm->trans_commit();
          } else {
            $this->status = 1201;
            $this->message = 'Berhasil';
            $this->cactm->trans_rollback();
          }
        }
        $this->cactm->trans_end();
      }
    }else{
      $this->status = 1221;
      $this->message = 'Tidak diberi akses kamera';
    }
    $this->__json_out($data);
  }

  public function jawab($utype = '')
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 401;
      $this->message = 'Harus login, silakan login / daftar dulu';
      $this->__json_out($data);
      return;
    }

    $cam = $this->cam->getByUserId($d['sess']->user->id);
    if (!isset($cam->id)) {
      $this->status = 1239;
      $this->message = 'Maaf anda belum apply atau proses seleksi anda sudah selesai.';
      $this->__json_out($data);
      return;
    }

    if(!isset($d['sess']->a_banksoal_id)){
      $this->status = 1278;
      $this->message = 'ID bank soal tidak valid';
      $this->__json_out($data);
      return;
    }

    $absm = $this->absm->getById($d['sess']->a_banksoal_id);
    if (!isset($absm->id)) {
      $this->status = 1238;
      $this->message = 'Data bank soal tidak valid';
      $this->__json_out($data);
      return;
    }

    $di = array();
    $di['b_user_id'] = $d['sess']->user->id;

    $jawabans = $this->input->post('jawaban');
    if (!is_array($jawabans)) $jawabans = array();

    $jawaban_l = $this->input->post('jawaban_l');
    if (!is_array($jawaban_l)) $jawaban_l = array();

    $jawaban_m = $this->input->post('jawaban_m');
    if (!is_array($jawaban_m)) $jawaban_m = array();

    if (count($jawabans) == 0 && $utype != 'kepribadian') {
      $this->status = 1208;
      $this->message = 'Tidak ada jawaban yang dikirim ke server';
      $this->__json_out($data);
      return;
    } elseif ((count($jawaban_m) == 0 || count($jawaban_l) == 0) && $utype == 'kepribadian') {
      $this->status = 1208;
      $this->message = 'Tidak ada jawaban yang dikirim ke server';
      $this->__json_out($data);
      return;
    }

    $ket = '';
    $poin = 0;
    $a_banksoal_id = 0;
    $dis = array();
    if (is_array($d['sess']->soal) && count($d['sess']->soal)) {
      $i = 0;
      foreach ($d['sess']->soal as $soal) {
        $di = array();
        $a_banksoal_id = $soal->a_banksoal_id;
        $jawaban = (isset($jawabans[$i])) ? trim(strip_tags($jawabans[$i])) : '';

        $di = array();
        if ($utype == 'iq') {
          $di['jawaban'] = (isset($jawabans[$soal->id])) ? trim(strip_tags($jawabans[$soal->id])) : '';
        } elseif ($utype == 'kepribadian') {
          $di['jawaban_l'] = isset($jawaban_l[$soal->id]) ? $jawaban_l[$soal->id] : 'NULL';
          $di['jawaban_m'] = isset($jawaban_m[$soal->id]) ? $jawaban_m[$soal->id] : 'NULL';
        } elseif ($utype == 'cs') {
          $di['jawaban'] = (isset($jawabans[$soal->id])) ? trim(strip_tags($jawabans[$soal->id])) : '';
        } else {
          $di['jawaban'] = (isset($jawabans[$i])) ? trim(strip_tags($jawabans[$i])) : '';
        }

        $di['b_user_id'] = $d['sess']->user->id;
        $di['a_banksoal_id'] = $a_banksoal_id;
        $di['b_soal_id'] = $soal->id;
        $di['c_apply_id'] = $cam->id;
        $di['cdate'] = 'NOW()';
        $di['b_soal_pilihan_id'] = 'NULL';
        if (count($soal->pilihans) && is_array($soal->pilihans)) {
          foreach ($soal->pilihans as $pil) {
            if ($utype == 'iq' && $di['jawaban'] == $pil->id) {
              $di['b_soal_pilihan_id'] = $pil->id;
              $di['jawaban'] = $pil->pilihan;

              $poin += (int) $pil->bobot;
            }
            if ($utype == 'kepribadian') {
              $di['jawaban'] = '';
              if ($di['jawaban_l'] == $pil->id) {
                $di['jawaban'] .= $pil->bobot_l . ',';
              } else if ($di['jawaban_m'] == $pil->id) {
                $di['jawaban'] .= $pil->bobot_m . ',';
              }
              $di['jawaban'] = rtrim($di['jawaban'], ',');
            }
            if ($utype == 'cs') {
              $pil->pilihan = trim(strip_tags($pil->pilihan));
              similar_text($pil->pilihan, $di['jawaban'], $percent);
              $bobot = 0;
              if ($percent >= 25 && $percent < 75) {
                $bobot = 1;
              } elseif ($percent >= 75) {
                $bobot = 2;
              }
              $poin += $bobot;
            }
          }
        }

        $dis[] = $di;
        $i++;
      }
    }

    if (count($dis) && is_array($dis)) {
      foreach($dis as $di){
        $catm = $this->catm->present($di['a_banksoal_id'], $di['b_user_id'], $di['c_apply_id'], $di['b_soal_id']);
        if(isset($catm->id)){
          $this->catm->update($catm->id, $di);
        }else{
          $this->catm->set($di);
        }
      }

      $string_hasil_tes = '[]';
      $json_hasil_tes = array();
      $hasil_tes = $this->bum->getHasilTes($d['sess']->user->id);
      if (strlen($hasil_tes)>4) {
        $json_hasil_tes = json_decode($hasil_tes);
        $is_found = 0;
        if(is_array($json_hasil_tes) && count($json_hasil_tes)){
          foreach ($json_hasil_tes as $kjht=>$vjht) {
            if ($json_hasil_tes[$kjht]->utype == $utype) {
              $json_hasil_tes[$kjht]->poin = $poin;
              $json_hasil_tes[$kjht]->ket = $ket;
              $is_found = 1;
              break;
            }
          }
          unset($kjht,$vjht);
        }


        if (!$is_found) {
          $hasil = new stdClass();
          $hasil->utype = $utype;
          $hasil->poin = $poin;
          $hasil->ket = $ket;
          $hasil->lolos1 = false;
          $hasil->lolos2 = false;
          $json_hasil_tes[] = $hasil;
          unset($hasil);
        }
      } else {
        $json_hasil_tes[0] = new stdClass();
        $json_hasil_tes[0]->utype = $utype;
        $json_hasil_tes[0]->poin = $poin;
        $json_hasil_tes[0]->ket = $ket;
        $json_hasil_tes[0]->lolos1 = false;
        $json_hasil_tes[0]->lolos2 = false;
      }

      $cam = $this->cam->getDetailByUserId($d['sess']->user->id);
      if($utype == 'iq'){
        $cam->min_iq = (int) $cam->min_iq;
        foreach($json_hasil_tes as $kjht=>$vjht){
          if($vjht->utype == $utype){
            $vjht->poin = (int) $vjht->poin;
            $nilai_iq = 0;
            $rtiq = $this->_resultTestIQ($vjht->poin);
            if(isset($rtiq->iq)){
              $nilai_iq = (int) $rtiq->iq;
            }
            if($nilai_iq >= $cam->min_iq){
              $json_hasil_tes[$kjht]->lolos1 = true;
              $json_hasil_tes[$kjht]->lolos2 = true;
              if(isset($rtiq->nama)){
                $json_hasil_tes[$kjht]->ket = $rtiq->nama;
              }
            }

            $nilai_iq = $nilai_iq + floor(($nilai_iq/100)*20);
            if($nilai_iq<=0) $nilai_iq = 0;
            if($nilai_iq >= $cam->min_iq){
              $json_hasil_tes[$kjht]->lolos1 = false;
              $json_hasil_tes[$kjht]->lolos2 = true;
            }
            break;
          }
        }
      }elseif($utype == 'cs'){
        $cam->min_iq = (int) $cam->min_iq;
        foreach($json_hasil_tes as $kjht=>$vjht){
          if($vjht->utype == $utype){
            $vjht->poin = floor($vjht->poin);
            $nilai_cs = 0;
            $rtiq = $this->_resultTestCS($vjht->poin);
            if(isset($rtiq->cs)){
              $nilai_cs = (int) $rtiq->cs;
            }
            if($nilai_cs >= $cam->min_cs){
              $json_hasil_tes[$kjht]->lolos1 = true;
              $json_hasil_tes[$kjht]->lolos2 = true;
            }
            break;
          }
        }
      }

      $string_hasil_tes = json_encode($json_hasil_tes);
      $this->bum->update($d['sess']->user->id, array('hasil_tes' => $string_hasil_tes));
    }


    $this->status = 200;
    $this->message = 'Berhasil';
    $this->__json_out($data);
  }

  public function jawab_selesai($utype = '')
  {
    $d = $this->__init();
    $data = array();
    if (!$this->user_login) {
      $this->status = 401;
      $this->message = 'Harus login, silakan login / daftar dulu';
      $this->__json_out($data);
      return;
    }

    $cam = $this->cam->getByUserId($d['sess']->user->id);
    if (!isset($cam->id)) {
      $this->status = 1239;
      $this->message = 'Maaf anda belum apply atau proses seleksi anda sudah selesai.';
      $this->__json_out($data);
      return;
    }

    if(!isset($d['sess']->a_banksoal_id)){
      $this->status = 1278;
      $this->message = 'ID bank soal tidak valid';
      $this->__json_out($data);
      return;
    }

    $absm = $this->absm->getById($d['sess']->a_banksoal_id);
    if (!isset($absm->id)) {
      $this->status = 1238;
      $this->message = 'Data bank soal tidak valid';
      $this->__json_out($data);
      return;
    }

    $di = array();
    $di['b_user_id'] = $d['sess']->user->id;

    $jawabans = $this->input->post('jawaban');
    if (!is_array($jawabans)) $jawabans = array();

    $jawaban_l = $this->input->post('jawaban_l');
    if (!is_array($jawaban_l)) $jawaban_l = array();

    $jawaban_m = $this->input->post('jawaban_m');
    if (!is_array($jawaban_m)) $jawaban_m = array();

    if (count($jawabans) == 0 && $utype != 'kepribadian') {
      $this->status = 1208;
      $this->message = 'Tidak ada jawaban yang dikirim ke server';
      $this->__json_out($data);
      return;
    } elseif ((count($jawaban_m) == 0 || count($jawaban_l) == 0) && $utype == 'kepribadian') {
      $this->status = 1208;
      $this->message = 'Tidak ada jawaban yang dikirim ke server';
      $this->__json_out($data);
      return;
    }

    $ket = '';
    $poin = 0;
    $a_banksoal_id = 0;
    $dis = array();
    if (is_array($d['sess']->soal) && count($d['sess']->soal)) {
      $i = 0;
      foreach ($d['sess']->soal as $soal) {
        $di = array();
        $a_banksoal_id = $soal->a_banksoal_id;
        $jawaban = (isset($jawabans[$i])) ? trim(strip_tags($jawabans[$i])) : '';

        $di = array();
        if ($utype == 'iq') {
          $di['jawaban'] = (isset($jawabans[$soal->id])) ? trim(strip_tags($jawabans[$soal->id])) : '';
        } elseif ($utype == 'kepribadian') {
          $di['jawaban_l'] = isset($jawaban_l[$soal->id]) ? $jawaban_l[$soal->id] : 'NULL';
          $di['jawaban_m'] = isset($jawaban_m[$soal->id]) ? $jawaban_m[$soal->id] : 'NULL';
        } elseif ($utype == 'cs') {
          $di['jawaban'] = (isset($jawabans[$soal->id])) ? trim(strip_tags($jawabans[$soal->id])) : '';
        } else {
          $di['jawaban'] = (isset($jawabans[$i])) ? trim(strip_tags($jawabans[$i])) : '';
        }

        $di['b_user_id'] = $d['sess']->user->id;
        $di['a_banksoal_id'] = $a_banksoal_id;
        $di['b_soal_id'] = $soal->id;
        $di['c_apply_id'] = $cam->id;
        $di['cdate'] = 'NOW()';
        $di['b_soal_pilihan_id'] = 'NULL';
        if (count($soal->pilihans) && is_array($soal->pilihans)) {
          foreach ($soal->pilihans as $pil) {
            if ($utype == 'iq' && $di['jawaban'] == $pil->id) {
              $di['b_soal_pilihan_id'] = $pil->id;
              $di['jawaban'] = $pil->pilihan;

              $poin += (int) $pil->bobot;
            }
            if ($utype == 'kepribadian') {
              $di['jawaban'] = '';
              if ($di['jawaban_l'] == $pil->id) {
                $di['jawaban'] .= $pil->bobot_l . ',';
              } else if ($di['jawaban_m'] == $pil->id) {
                $di['jawaban'] .= $pil->bobot_m . ',';
              }
              $di['jawaban'] = rtrim($di['jawaban'], ',');
            }
            if ($utype == 'cs') {
              $pil->pilihan = trim(strip_tags($pil->pilihan));
              similar_text($pil->pilihan, $di['jawaban'], $percent);
              $bobot = 0;
              if ($percent >= 25 && $percent < 75) {
                $bobot = 1;
              } elseif ($percent >= 75) {
                $bobot = 2;
              }
              $poin += $bobot;
            }
          }
        }

        $dis[] = $di;
        $i++;
      }
    }

    if (count($dis) && is_array($dis)) {
      foreach($dis as $di){
        $catm = $this->catm->present($di['a_banksoal_id'], $di['b_user_id'], $di['c_apply_id'], $di['b_soal_id']);
        if(isset($catm->id)){
          $this->catm->update($catm->id, $di);
        }else{
          $this->catm->set($di);
        }
      }

      $castm = $this->castm->getCurrent($a_banksoal_id, $d['sess']->user->id, $cam->id);
      if (count($castm) && is_array($castm)) {
        foreach ($castm as $buss) {
          $this->castm->update($buss->id, array('is_done' => 1));
        }
      }

      $string_hasil_tes = '[]';
      $json_hasil_tes = array();
      $hasil_tes = $this->bum->getHasilTes($d['sess']->user->id);
      if (strlen($hasil_tes)>4) {
        $json_hasil_tes = json_decode($hasil_tes);
        $is_found = 0;
        if(is_array($json_hasil_tes) && count($json_hasil_tes)){
          foreach ($json_hasil_tes as $kjht=>$vjht) {
            if ($json_hasil_tes[$kjht]->utype == $utype) {
              $json_hasil_tes[$kjht]->poin = $poin;
              $json_hasil_tes[$kjht]->ket = $ket;
              $is_found = 1;
              break;
            }
          }
          unset($kjht,$vjht);
        }


        if (!$is_found) {
          $hasil = new stdClass();
          $hasil->utype = $utype;
          $hasil->poin = $poin;
          $hasil->ket = $ket;
          $hasil->lolos1 = false;
          $hasil->lolos2 = false;
          $json_hasil_tes[] = $hasil;
          unset($hasil);
        }
      } else {
        $json_hasil_tes[0] = new stdClass();
        $json_hasil_tes[0]->utype = $utype;
        $json_hasil_tes[0]->poin = $poin;
        $json_hasil_tes[0]->ket = $ket;
        $json_hasil_tes[0]->lolos1 = false;
        $json_hasil_tes[0]->lolos2 = false;
      }

      $cam = $this->cam->getDetailByUserId($d['sess']->user->id);
      if($utype == 'iq'){
        $cam->min_iq = (int) $cam->min_iq;
        foreach($json_hasil_tes as $kjht=>$vjht){
          if($vjht->utype == $utype){
            $vjht->poin = (int) $vjht->poin;
            $nilai_iq = 0;
            $rtiq = $this->_resultTestIQ($vjht->poin);
            if(isset($rtiq->iq)){
              $nilai_iq = (int) $rtiq->iq;
            }
            if($nilai_iq >= $cam->min_iq){
              $json_hasil_tes[$kjht]->lolos1 = true;
              $json_hasil_tes[$kjht]->lolos2 = true;
              if(isset($rtiq->nama)){
                $json_hasil_tes[$kjht]->ket = $rtiq->nama;
              }
            }

            $nilai_iq = $nilai_iq + floor(($nilai_iq/100)*20);
            if($nilai_iq<=0) $nilai_iq = 0;
            if($nilai_iq >= $cam->min_iq){
              $json_hasil_tes[$kjht]->lolos1 = false;
              $json_hasil_tes[$kjht]->lolos2 = true;
            }
            break;
          }
        }
      }elseif($utype == 'cs'){
        $cam->min_iq = (int) $cam->min_iq;
        foreach($json_hasil_tes as $kjht=>$vjht){
          if($vjht->utype == $utype){
            $vjht->poin = floor($vjht->poin);
            $nilai_cs = 0;
            $rtiq = $this->_resultTestCS($vjht->poin);
            if(isset($rtiq->cs)){
              $nilai_cs = (int) $rtiq->cs;
            }
            if($nilai_cs >= $cam->min_cs){
              $json_hasil_tes[$kjht]->lolos1 = true;
              $json_hasil_tes[$kjht]->lolos2 = true;
            }
            break;
          }
        }
        // only update lamar stat no for test CS and test skill.
        // PS. Test Skill not done yet 2021-12-09
        $this->bum->update($d['sess']->user->id, array('apply_statno' => '2'));
      }

      $string_hasil_tes = json_encode($json_hasil_tes);
      $this->bum->update($d['sess']->user->id, array('hasil_tes' => $string_hasil_tes));
    }

    if($utype == 'cs'){
      $this->bum->update($d['sess']->user->id, array('apply_statno' => '2'));
      $this->_setULog($d['sess']->user->id, 10);
    }else{
      if($utype == 'iq'){
        $this->_setULog($d['sess']->user->id, 18);
      }else{
        $this->_setULog($d['sess']->user->id, 22);
      }
      $tes_session = $this->castm->getDoneByApplyId($cam->id);
      if(count($tes_session)==3){
        $bum = $this->bum->getById($d['sess']->user->id);
        $hasil_tes = json_decode($bum->hasil_tes);
        if(isset($hasil_tes[0]->utype)){
          $is_lolos = 0;
          foreach($hasil_tes as $ht){
            if($ht->utype == 'kepribadian'){
              $is_lolos++;
            }else{
              if(isset($ht->lolos1) && isset($ht->lolos2)){
                if($ht->lolos1 == true || $ht->lolos2 == true){
                  $is_lolos++;
                }
              }
            }
          }
          if($is_lolos==3){
            $this->bum->update($d['sess']->user->id, array('apply_statno' => '5'));
          }
        }
      }else{
        $this->bum->update($d['sess']->user->id, array('apply_statno' => '3'));
      }
    }


    $this->status = 200;
    $this->message = 'Berhasil';
    $this->__json_out($data);
  }
}
