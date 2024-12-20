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
$category = array(
  "hist"  => "История",
  "lit" => "Литература",
  "Slave" => "Власть",
  "memorial" => "Памятники",
  "weather" => "Погода",
  "musei" => "Музеи",
  "Year450" => "Архитектура",
  "industry" => "Промышленность",
  "hero" => "Герои",
  "business" => "Бизнес",
  "educat" => "Образование",
  "honor" => "Гордость",
  "rest" => "Отдых",
  "theatre" => "Театры",
  "region57" => "Область",
  "social" => "Социальное",
  "sport" => "Спорт",
  "fisher" => "Рыбалка и грибы",
  "internet" => "Интернет",
  "contrast" => "Контрасты",
  "church" => "Церкви",
  "Projects" => "Проекты",
  "School" => "Школы",
  "Roads" => "Дороги",
  "obychay" => "Обычаи"
);

mysqli_query($dbcnx, "SET NAMES utf8");
define('HIST', 1 << 0);   // 0001 история, литература, герои
define('INDS', 1 << 1); // 0010 промышленность, сх,
define('REST', 1 << 2);   // 0100 развлечения, отдых , места, область
define('POWE', 1 << 3); // 10000 администрация
define('CULT', 1 << 4); // 100000 театры, музеи
define('SPRT', 1 << 5); //спорт
define('BSNS', 1 << 6);  //бизнес
define('SOCL', 1 << 7);  //социальная, образование
define('ALL', HIST | INDS | REST | POWE | CULT | SPRT | BSNS| SOCL); // 1111

////районы
$region = array('Орловский','Болховский','Мценский'	,'Залегощенский','Корсаковский','Новосильский','Краснозоренский','Новодеревеньковский','Верховский','Покровский','Должанский',
  'Колпнянский','Глазуновский','Малоархангельский','Свердловский','Дмитровский','Кромской','Хотынецкий','Сосковский','Шаблыкинский','Троснянский','Знаменский','Ливенский','Урицкий','Неизвестно');
//////
$iv = "80t43poas1df8lpt";
$method = 'aes-128-cbc';
$pass = '12342132545';// тут одинаковый пароль, так как пользователи могут ввести разные пароли
$month = array('Январь','Февраль','Март','Апрель', 'Май','Июнь','Июль', 'Август','Сентябрь','Октябрь', 'Ноябрь','Декабрь');

function smash($dbcnx, $log, $arg_1)
{
  $result = mysqli_query($dbcnx,"SELECT date FROM user WHERE BINARY name='$log'");
  if (!$result) {
    die('Неверный запрос:'.$dbcnx.'--' .$log. mysqli_error($dbcnx));
  }
  $myrow = mysqli_fetch_array($result);
  if (empty($myrow['date'])) {
    return "Извините, не найдено имя в базе.";
  }
  ///cложить это с тем, что пришло
  $dt = md5($myrow['date']);
  $len = strlen($dt);
  for ($i = 0; $i < $len; $i++){
    $temp[$i] = chr(ord($dt[$i]) ^ ord($arg_1[$i]));
  }
  $buff = implode($temp);
  //получаем хэш пароля
  return $buff;
}
function ss_id($arg_1, $arg_2)
{
  $r = "80t43poassdflk43sd6pof24kssdo2lfosfs4fslss45lcssfssjsvnknvdvn1df8lpt";
  $len1 = strlen($arg_1);
  $len2 = strlen($arg_2);
  $temp1 = array ();
  $j = 0;
  if ($len1 > $len2) {
    for ($i = $len2; $i < $len1; $i++){
      $arg_2[$i] =  $r[$j];
      $j++;
    }
  }
  for ($i = 0; $i < $len1; $i++){
    $temp1[$i] = chr(ord($arg_1[$i]) ^ ord($arg_2[$i]));
  }
  $buff = implode($temp1);
  return $buff;
}

function generate()
{
  $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
  $max=10;
  $size=StrLen($chars)-1;
  $password=null;
  while($max--)
    $password.=$chars[rand(0,$size)];
  return $password;
}

function prov($per){
  if (isset($per)) {
    $per = stripslashes($per);
    $per = htmlspecialchars($per);
    $per = addslashes($per);
  }
  return $per;
}

function resize($file_input, $file_output, $w_o, $h_o, $percent = false) {
  list($w_i, $h_i, $type) = getimagesize($file_input);
  if (!$w_i || !$h_i) {
    echo 'Невозможно получить длину и ширину изображения';
    return;
  }
  $types = array('','gif','jpeg','png');
  $ext = $types[$type];
  if ($ext) {
    $func = 'imagecreatefrom'.$ext;
    $img = $func($file_input);
  } else {
    echo 'Некорректный формат файла';
    return;
  }
  if ($percent) {
    $w_o *= $w_i / 100;
    $h_o *= $h_i / 100;
  }
  if (!$h_o) $h_o = $w_o/($w_i/$h_i);
  if (!$w_o) $w_o = $h_o/($h_i/$w_i);
  $img_o = imagecreatetruecolor($w_o, $h_o);
  imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
  if ($type == 2) {
    imagejpeg($img_o,$file_output,100);
  } else {
    $func = 'image'.$ext;
    $func($img_o,$file_output);
  }
  imagedestroy($img_o);
}

function crop($file_input, $file_output, $crop = 'square',$percent = false) {
  list($w_i, $h_i, $type) = getimagesize($file_input);
  if (!$w_i || !$h_i) {
    echo 'Невозможно получить длину и ширину изображения';
    return;
  }
  $types = array('','gif','jpeg','png');
  $ext = $types[$type];
  if ($ext) {
    $func = 'imagecreatefrom'.$ext;
    $img = $func($file_input);
  } else {
    echo 'Некорректный формат файла';
    return;
  }
  if ($crop == 'square') {
    $min = $w_i;
    if ($w_i > $h_i) $min = $h_i;
    $w_o = $h_o = $min;
  } else {
    list($x_o, $y_o, $w_o, $h_o) = $crop;
    if ($percent) {
      $w_o *= $w_i / 100;
      $h_o *= $h_i / 100;
      $x_o *= $w_i / 100;
      $y_o *= $h_i / 100;
    }
    if ($w_o < 0) $w_o += $w_i;
    $w_o -= $x_o;
    if ($h_o < 0) $h_o += $h_i;
    $h_o -= $y_o;
  }
  $img_o = imagecreatetruecolor($w_o, $h_o);
  imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
  if ($type == 2) {
    imagejpeg($img_o,$file_output,100);

  } else {
    $func = 'image'.$ext;
    $func($img_o,$file_output);
  }
  imagedestroy($img_o);
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) == $date;
}
?>
