<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


$agency = '';
if(isset($_GET['agency'])) {
  $agency = $_GET['agency'];
}

//include ("../../../pages/const.php");
include("../../const.php");
$result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE agency= '$agency' ORDER BY id ASC LIMIT 5");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}

echo json_encode($arr);
