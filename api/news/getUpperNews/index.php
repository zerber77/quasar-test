<?php
$agency = '';
$id = '';
if(isset($_GET['agency'])) {
  $agency = $_GET['agency'];
}
if(isset($_GET['l_id'])) {
  $id = $_GET['l_id'];
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
$result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE agency= '$agency' && id > '$id' ORDER BY id ASC LIMIT 5");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}

echo json_encode($arr);
