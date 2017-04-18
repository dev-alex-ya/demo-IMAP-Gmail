<?php
$file = file('agents.csv', FILE_IGNORE_NEW_LINES);



function insertAgent($id, $name, $bpid){
  $_id = (integer) $id;
  $_name = $name;
  $_bpid = $bpid;


  $dbconn = new PDO('mysql:host=host; dbname=dbnametest', 'db_user', 'db_password', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $stmt = $dbconn->prepare("INSERT INTO agents (id, bpid, name) VALUES (:id, :bpid, :name)");
  $stmt->bindParam("id", $_id);
  $stmt->bindParam("bpid", $_bpid);
  $stmt->bindParam("name", $_name);
  $stmt->execute();
}


foreach ($file as $str) {
  $csv = str_getcsv(iconv("Windows-1251", "UTF-8", $str), '|');
  insertAgent($csv[0], $csv[2], $csv[1]);
}
