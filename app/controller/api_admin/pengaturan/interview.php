<?php
class Interview extends JI_Controller
{
  public $is_updated = 0; //validasi jika ada data yang diupdate ketika import excel

  public function __construct()
  {
    parent::__construct();
    $this->load("api_admin/b_interview_aspeknilai_model", 'bivanm');
  }
  public function aspeknilai_seed(){
    foreach($this->bivanm->getLevelJabatan() as $level){
      foreach($this->bivanm->getItemInterview() as $item){
        $bivanm = $this->bivanm->getByAljIdAiiId($level->id,$item->id);
        $di = array();
        $di['a_leveljabatan_id'] = $level->id;
        $di['a_iteminterview_id'] = $item->id;
        if($level->nama == 'Manager'){
          $di['nilai'] = 5;
        }elseif($level->nama == 'Assistant Manager'){
          $di['nilai'] = 4;
        }elseif($level->nama == 'Supervisor'){
          $di['nilai'] = 3;
        }else{
          $di['nilai'] = 2;
        }
        if(isset($bivan->id)){
          $this->bivanm->update($bivan->id,$di);
        }else{
          $this->bivanm->set($di);
        }
      }
    }
  }
}
