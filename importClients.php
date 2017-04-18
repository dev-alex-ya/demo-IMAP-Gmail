<?php
$file = file("clients.csv", FILE_IGNORE_NEW_LINES);

function insertAgent($id, $name, $address, $agentid, $phone){
  $_id = (integer) $id;
  $_name = $name;
  $_address = $address;
  $_agentid = (integer) $agentid;
  $_phone = $phone;


  $dbconn = new PDO('mysql:host=host; dbname=dbnametest', 'db_user', 'db_password', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $stmt = $dbconn->prepare("INSERT INTO clients (id, name, address, agentid, phone) VALUES (:id, :name, :address, :agentid, :phone)");
  $stmt->bindParam("id", $_id);
  $stmt->bindParam("name", $_name);
  $stmt->bindParam("address", $_address);
  $stmt->bindParam("agentid", $_agentid);
  $stmt->bindParam("phone", $_phone);
  $stmt->execute();
}


foreach ($file as $str) {
  $csv = str_getcsv(iconv("Windows-1251", "UTF-8", $str), '|');
  insertAgent($csv[0], $csv[1], $csv[2], $csv[3], $csv[4]);
}
