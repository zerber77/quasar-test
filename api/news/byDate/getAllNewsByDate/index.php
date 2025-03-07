<?php

header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$date = '';
if(isset($_GET['date'])) {
  $date = $_GET['date'];
}

//include ("../../../pages/const.php");
include("../../../const.php");
$result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE time_new LIKE '$date%' ");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}
echo json_encode($arr);

