<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

//include ("../../../pages/const.php");
include("../../const.php");
$result = mysqli_query($dbcnx, "SELECT DISTINCT agency FROM mynews");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}

echo json_encode($arr);
