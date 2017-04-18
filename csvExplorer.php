#!/usr/bin/php
<?php
require 'GetListFiles.php';
require 'entities/DataBase.php';


$all_files = array();
GetListFiles("/home/alex/Public/mail", $all_files);
print_r($all_files);

foreach ($all_files as $key => $file) {
  if($fileOpened = file($file)){
    $csvAsArray = [];

    foreach ($fileOpened as $key => $str) {
      $csvAsArray[$key] = str_getcsv($str , '|');
      db_routing($csvAsArray[$key]);
    }
    //print_r(date("Y-m-d", strtotime('05.04.2017')));
  }
  else{
    echo 'error';
  }
}
