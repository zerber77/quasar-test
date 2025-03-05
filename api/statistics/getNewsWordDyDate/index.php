<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$word = '';
if(isset($_GET['word'])) $word = $_GET['word'];

$date = '';
if(isset($_GET['date'])) $date = $_GET['date'];

$strong = '';
if(isset($_GET['strong'])) $strong = $_GET['strong'];

//include ("../../../pages/const.php");
include("../../const.php");
if ($strong == 'true') $result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE time_new LIKE '$date%' AND (text LIKE '% $word %' OR head LIKE '% $word %')");
else $result = mysqli_query($dbcnx, "SELECT * FROM mynews WHERE time_new LIKE '$date%' AND (text LIKE '%$word%' OR head LIKE '%$word%')");
$arr = array();
while ($row = mysqli_fetch_assoc($result)) {
  $arr[] = $row;
}

echo json_encode($arr);
