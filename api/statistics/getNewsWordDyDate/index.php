<?php
$word = '';
if(isset($_GET['word'])) {
  $word = $_GET['word'];
}
$date = '';
if(isset($_GET['date'])) {
  $date = $_GET['date'];
}
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
session_set_cookie_params(3600*24*3);
session_start();
//include ("../../../pages/const.php");
include("../../const.php");
$result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE time_new LIKE '$date%' AND (text LIKE '%$word%' OR head LIKE '%$word%')");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}

echo json_encode($arr);
