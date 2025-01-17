<?php
$date = '';
$word = '';

if(isset($_GET['date'])) {
  $date = $_GET['date'];
}
if(isset($_GET['word'])) {
  $word = $_GET['word'];
}
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
session_set_cookie_params(3600*24*3);
session_start();

include("../../const.php");
$result = mysqli_query($dbcnx, "SELECT COUNT(*) FROM mynews WHERE time_new LIKE '$date%' AND (text LIKE '%$word%' OR head LIKE '%$word%')");//

$count = mysqli_fetch_array($result);
//echo $word;
echo json_encode($count[0]);

