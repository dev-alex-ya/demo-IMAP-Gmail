<?php

function db_routing($arr_data){
  switch ($arr_data[0]) {
    case "DT":
    case "DI":
      insertDT($arr_data);
      break;
    case "HD":
    case "HI":
      insertHD($arr_data);
      break;
    case "CL":
      print_r( 'клиент');
      print_r("\n");
      break;
    case "DD":
      print_r( 'dd');
      print_r("\n");
      break;
    case "FP":
      print_r( 'fp');
      print_r("\n");
      break;
    case "GC":
      print_r( 'категория товара');
      break;
    case "PR":
      print_r( 'pr');
      break;
    case "PT":
      print_r( 'pt');
      break;
    default:
      print_r('default - '.$arr_data[0]);
      break;
  }
}



  /*  [6] => Array
        (
            [0] => DT
            [1] => 2323
            [2] => 919
            [3] => 22216000
            [4] => 5
            [5] =>
            [6] =>
            [7] => 17.83
            [8] =>
        )
*/
function insertDT($arr_data) {
  $agentid = (integer)$arr_data[1];
  $orderid = (integer)$arr_data[2];
  $goodsid = (integer)$arr_data[3];
  $quantity = (integer)$arr_data[4];
  $cost = (double)$arr_data[7];

  $db = new PDO('mysql:host=localhost;dbname=test', 'root', '1', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $stmt = $db->prepare("INSERT INTO dt (orderid, agentid, goodsid, quantity, cost) VALUES (:orderid, :agentid, :goodsid, :quantity, :cost)");
  $stmt->bindParam("orderid", $orderid );
  $stmt->bindParam("agentid", $agentid );
  $stmt->bindParam("goodsid", $goodsid );
  $stmt->bindParam("quantity", $quantity );
  $stmt->bindParam("cost", $cost );
  $stmt->execute();
}


/* [5] => Array
    (
        [0] => HD
        [1] => 924
        [2] => 30007
        [3] => 05.04.2017
        [4] =>
        [5] => 919.84
        [6] => �� ������� ���� ���������� ���� ������� 0997864532
        [7] => ���
        [8] => 06.04.2017
        [9] => 2323
        [10] => 6
        [11] => ����
        [12] => 10:54:35
        [13] => 10:56:26
        [14] => 10:56:33
        [15] => -9223372036854775808
        [16] => 0
        [17] => 0
        [18] =>
    )
*/
function insertHD($arr_data){
  $id = (integer) $arr_data[1];
  $clientid = (integer) $arr_data[2];
  $datein = date("Y-m-d", strtotime($arr_data[3]));
  $cost = stripos((double) $arr_data[5], '.') ? (double) $arr_data[5] : ((double) $arr_data[5]).".0";
  $comment = iconv( "Windows-1251", "UTF-8", $arr_data[6]);
  $pricetypename = iconv( "Windows-1251", "UTF-8", $arr_data[7]);
  $dateout = date("Y-m-d", strtotime($arr_data[8]));
  $agentid = (integer) $arr_data[9];
  $pricetypeid = $arr_data[10];
  $scalename = iconv( "Windows-1251", "UTF-8", $arr_data[11]);
  $timefinish = $arr_data[13];
  $timestart = $arr_data[12];
  $timesend = $arr_data[14];

  $db = new PDO('mysql:host=localhost;dbname=test', 'root', '1', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $stmt = $db->prepare("INSERT INTO hd (id, clientid, datein, cost, comment, pricetypename, dateout, agentid,".
  " pricetypeid, scalename, timestart, timefinish, timesend) ".
  "VALUES (:id, :clientid, :datein, :cost, :comment, :pricetypename, :dateout, :agentid,".
  " :pricetypeid, :scalename, :timestart, :timefinish, :timesend)");
  $stmt->bindParam("id", $id);
  $stmt->bindParam("clientid", $clientid);
  $stmt->bindParam("datein", $datein);
  $stmt->bindParam("cost", $cost);
  $stmt->bindParam("comment", $comment);
  $stmt->bindParam("pricetypename", $pricetypename);
  $stmt->bindParam("dateout", $dateout);
  $stmt->bindParam("agentid", $agentid);
  $stmt->bindParam("pricetypeid", $pricetypeid);
  $stmt->bindParam("scalename", $scalename);
  $stmt->bindParam("timestart", $timestart);
  $stmt->bindParam("timefinish", $timefinish);
  $stmt->bindParam("timesend", $timesend);
  $stmt->execute();
}
