<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("../../const.php");
require("../../auth/VerifyToken.php");

$token = verifyToken($key);
if (array_key_exists('error', $token)) {
  echo json_encode ($token);
  exit;
}

$wordRU = '';
if(isset($_GET['wordRU'])) $wordRU = $_GET['wordRU'];
$wordEN = '';
if(isset($_GET['wordEN'])) $wordEN = $_GET['wordEN'];
$date = '';
if(isset($_GET['date'])) $date = $_GET['date'];



$result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE time_new LIKE '$date%' AND ((text LIKE '%$wordRU%' OR head LIKE '%$wordRU%') OR (text LIKE '%$wordEN%' OR head LIKE '%$wordEN%'))");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}

echo json_encode($arr);
