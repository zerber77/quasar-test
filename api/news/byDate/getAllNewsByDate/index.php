<?php

header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("../../../const.php");
require("../../../auth/VerifyToken.php");

$token_error = verifyToken($key);
if (array_key_exists('error', $token_error)) {
  echo json_encode ($token_error);
  exit;
}

$date = '';
if(isset($_GET['date'])) {
  $date = $_GET['date'];
}

//include ("../../../pages/const.php");

$result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE time_new LIKE '$date%' ");
$arr = [];
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}
echo json_encode($arr);

