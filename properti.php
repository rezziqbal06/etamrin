SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

INSERT INTO `virtual_aliases` (domain_id`, `source`, `destination`) VALUES
<?php
for($i=1;$i<=15;$i++){
  $num = str_pad($i,3,'0',STR_PAD_LEFT);
  echo "(14, 'sbp$num@cenah.co.id', 'sbp.test@cenah.co.id'),\r\n";
}
?>;

SET FOREIGN_KEY_CHECKS=1;
