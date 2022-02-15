var is_login = 0;
var is_jobslist = 0;

<?php $this->getThemeElement('register/_form_bottom',$__forward);?>
setTimeout(function(){
  let b = getBrowser();
  if(!b.match('Chrome') && !b.match('Firefox')){
    alert('Sistem ini hanya mendukung Google Chrome atau Firefox\nKlik OK untuk membaca ketentuan lengkapnya...');
    window.location = '<?=base_url('term_condition/#browser')?>';
  }
},1234);
