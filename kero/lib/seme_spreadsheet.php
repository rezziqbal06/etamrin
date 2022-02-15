<?php

//loading library
$vendorDirPath = (SEMEROOT . 'kero/lib/phpoffice/vendor/');
$vendorDirPath = realpath($vendorDirPath);
require_once $vendorDirPath . '/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Seme_Spreadsheet
{
  public function __construct()
  {
  }

  public function newReader()
  {
    return new Xlsx();
  }
}
