<?php
$agency = '';
if(isset($_GET['agency'])) {
  $agency = $_GET['agency'];
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
$result = mysqli_query($dbcnx, "SELECT COUNT(*) FROM mynews WHERE agency= '$agency'");
$count = mysqli_fetch_array($result);

echo json_encode($count[0]);
