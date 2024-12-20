<?php
$dblocation = "localhost";
$dbname = "u0246426_default";
$dbuser = "root";
$dbpasswd = "";

//$dblocation = "localhost";
//$dbname = "u0246426_default";
//$dbuser = "u0246426_default";
//$dbpasswd = "reg30577";
$dbcnx = @mysqli_connect($dblocation,$dbuser,$dbpasswd, $dbname);
if ($dbcnx -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>
