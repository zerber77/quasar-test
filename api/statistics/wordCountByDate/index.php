<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include("../../const.php");
require("../../auth/VerifyToken.php");
$token_error = verifyToken($key);
if (array_key_exists('error', $token_error)) {
  echo json_encode ($token_error);
  exit;
}
$date = '';
$word = '';
if(isset($_GET['date'])) {
  $date = $_GET['date'];
}
if(isset($_GET['word'])) {
  $word = $_GET['word'];
}

$result = mysqli_query($dbcnx, "SELECT COUNT(*) FROM mynews WHERE time_new LIKE '$date%' AND (text LIKE '%$word%' OR head LIKE '%$word%')");//

$count = mysqli_fetch_array($result);
//echo $word;
echo json_encode($count[0]);

