<?php
Class Tgl {
  public $datetime;
  public $bulan;
  public $bulan_ke;
  public $hari;
  public $hari_ke;

  public function __construct(){
  }
  public function set($datetime){
    $this->datetime = $datetime;
    if (is_null($this->datetime) || empty($this->datetime)) {
      $this->datetime='now';
    }
  }
  public function get($utype='hari_tanggal'){
    $this->datetime = $datetime;
    if (is_null($this->datetime) || empty($this->datetime)) {
      $this->datetime='now';
    }
    $stt = strtotime($this->datetime);
    $this->bulan_ke = date('n', $stt);
    $this->bulan = 'Desember';
    switch ($this->bulan_ke) {
      case '1':
      $this->bulan = 'Januari';
      break;
      case '2':
      $this->bulan = 'Februari';
      break;
      case '3':
      $this->bulan = 'Maret';
      break;
      case '4':
      $this->bulan = 'April';
      break;
      case '5':
      $this->bulan = 'Mei';
      break;
      case '6':
      $this->bulan = 'Juni';
      break;
      case '7':
      $this->bulan = 'Juli';
      break;
      case '8':
      $this->bulan = 'Agustus';
      break;
      case '9':
      $this->bulan = 'September';
      break;
      case '10':
      $this->bulan = 'Oktober';
      break;
      case '11':
      $this->bulan = 'November';
      break;
      default:
      $this->bulan = 'Desember';
    }
    $this->hari_ke = date('N', $stt);
    $this->hari = 'Minggu';
    switch ($this->hari_ke) {
      case '1':
      $this->hari = 'Senin';
      break;
      case '2':
      $this->hari = 'Selasa';
      break;
      case '3':
      $this->hari = 'Rabu';
      break;
      case '4':
      $this->hari = 'Kamis';
      break;
      case '5':
      $this->hari = 'Jumat';
      break;
      case '6':
      $this->hari = 'Sabtu';
      break;
      default:
      $this->hari = 'Minggu';
    }
    $utype == strtolower($utype);
    if ($utype=="hari") {
      return $this->hari;
    }
    if ($utype=="jam") {
      return date('H:i', $stt).' WIB';
    }
    if ($utype=="bulan") {
      return $this->bulan;
    }
    if ($utype=="tahun") {
      return date('Y', $stt);
    }
    if ($utype=="bulan_tahun") {
      return $this->bulan.' '.date('Y', $stt);
    }
    if ($utype=="tanggal") {
      return ''.date('d', $stt).' '.$this->bulan.' '.date('Y', $stt);
    }
    if ($utype=="tanggal_jam") {
      return ''.date('d', $stt).' '.$this->bulan.' '.date('Y H:i', $stt).' WIB';
    }
    if ($utype=="hari_tanggal") {
      return $this->hari.', '.date('d', $stt).' '.$this->bulan.' '.date('Y', $stt);
    }
    if ($utype=="hari_tanggal_jam") {
      return $this->hari.', '.date('d', $stt).' '.$this->bulan.' '.date('Y H:i', $stt).' WIB';
    }
  }
  public function convert($datetime,$utype='hari_tanggal'){
    $this->datetime = $datetime;
    if (is_null($this->datetime) || empty($this->datetime)) {
      $this->datetime='now';
    }
    $stt = strtotime($this->datetime);
    $this->bulan_ke = date('n', $stt);
    $this->bulan = 'Desember';
    switch ($this->bulan_ke) {
      case '1':
      $this->bulan = 'Januari';
      break;
      case '2':
      $this->bulan = 'Februari';
      break;
      case '3':
      $this->bulan = 'Maret';
      break;
      case '4':
      $this->bulan = 'April';
      break;
      case '5':
      $this->bulan = 'Mei';
      break;
      case '6':
      $this->bulan = 'Juni';
      break;
      case '7':
      $this->bulan = 'Juli';
      break;
      case '8':
      $this->bulan = 'Agustus';
      break;
      case '9':
      $this->bulan = 'September';
      break;
      case '10':
      $this->bulan = 'Oktober';
      break;
      case '11':
      $this->bulan = 'November';
      break;
      default:
      $this->bulan = 'Desember';
    }
    $this->hari_ke = date('N', $stt);
    $this->hari = 'Minggu';
    switch ($this->hari_ke) {
      case '1':
      $this->hari = 'Senin';
      break;
      case '2':
      $this->hari = 'Selasa';
      break;
      case '3':
      $this->hari = 'Rabu';
      break;
      case '4':
      $this->hari = 'Kamis';
      break;
      case '5':
      $this->hari = 'Jumat';
      break;
      case '6':
      $this->hari = 'Sabtu';
      break;
      default:
      $this->hari = 'Minggu';
    }
    $utype == strtolower($utype);
    if ($utype=="hari") {
      return $this->hari;
    }
    if ($utype=="jam") {
      return date('H:i', $stt).' WIB';
    }
    if ($utype=="bulan") {
      return $this->bulan;
    }
    if ($utype=="tahun") {
      return date('Y', $stt);
    }
    if ($utype=="bulan_tahun") {
      return $this->bulan.' '.date('Y', $stt);
    }
    if ($utype=="tanggal") {
      return ''.date('d', $stt).' '.$this->bulan.' '.date('Y', $stt);
    }
    if ($utype=="tanggal_jam") {
      return ''.date('d', $stt).' '.$this->bulan.' '.date('Y H:i', $stt).' WIB';
    }
    if ($utype=="hari_tanggal") {
      return $this->hari.', '.date('d', $stt).' '.$this->bulan.' '.date('Y', $stt);
    }
    if ($utype=="hari_tanggal_jam") {
      return $this->hari.', '.date('d', $stt).' '.$this->bulan.' '.date('Y H:i', $stt).' WIB';
    }
  }
}
