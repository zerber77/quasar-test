<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
//header("Access-Control-Max-Age: 86400");
//session_set_cookie_params(3600 * 24 * 3);
//session_start();
include("../../const.php");
require("../../auth/VerifyToken.php");

$date = '';
if(isset($_GET['date'])) {
  $date = $_GET['date'];
}

$result = mysqli_query($dbcnx, "SELECT * FROM word_frequency WHERE date LIKE '$date%'");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}

if ($error == '') echo json_encode(['token_data' => $decoded, 'data' => $arr]);
else echo json_encode(['Error' => $error]);
