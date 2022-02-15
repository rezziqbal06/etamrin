<?php

class IQresult extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("front/b_user_model", "bum");
        $this->load("front/c_apply_model", "clm");
        $this->current_menu = 'dashboard';
    }
    public function index()
    {
      ?><table><?php
      echo '<tr>';
      echo '<th>Nilai Hasil</th>';
      echo '<th>Nama IQ</th>';
      echo '<th>Nilai IQ</th>';
      echo '</tr>';
      foreach($this->_resultTestIQ() as $kiq=>$viq){
        echo '<tr>';
        echo '<th>'.$kiq.'</th>';
        echo '<th>'.$viq->nama.'</th>';
        echo '<th>'.$viq->iq.'</th>';
        echo '</tr>';
      }
      ?></table><?php
    }
    public function nilai($nilai='0')
    {
      $nilai = (int) $nilai;
      if($nilai<=0) $nilai = 0;
      $nilai_iq = $this->_resultTestIQ($nilai);
      ?><table><?php
      echo '<tr>';
      echo '<th>Nilai Hasil</th>';
      echo '<th>Nama IQ</th>';
      echo '<th>Nilai IQ</th>';
      echo '</tr>';
      echo '<tr>';
      echo '<th>'.$nilai.'</th>';
      echo '<th>'.(isset($nilai_iq->nama) ? $nilai_iq->nama : '-').'</th>';
      echo '<th>'.(isset($nilai_iq->iq) ? $nilai_iq->iq : '-').'</th>';
      echo '</tr>';
      ?></table><?php
    }
    public function seed()
    {
      ?>
      TRUNCATE `a_iqresult`;
      INSERT INTO a_iqresult (id,nilai,nama) VALUES
      <?php
      $i=0;
      foreach($this->_resultTestIQ() as $kiq=>$viq){
        if($i>0) echo ',';
        echo '('.$kiq.', '.$viq->iq.',"'.$viq->nama.'")';
        $i++;
      }
      ?>
      ;
      <?php
    }
}
