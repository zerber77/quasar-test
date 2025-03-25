<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


include("../../const.php");
//require("../auth/VerifyToken.php");


//$token = verifyToken($key);
//if (array_key_exists('error', $token)) {
//  echo json_encode ($token);
//  exit;
//}

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO("mysql:host=$db_analitika;dbname=$dbname_analitika;charset=utf8mb4", $dbuser_analitika, $dbpasswd_analitika, $options);
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}



  // 
  try {
    $stmt = $pdo->prepare("SELECT u.name, u.login, r.review, r.created_at  FROM reviews AS r  LEFT JOIN users u ON r.user_id = u.user_id");
    $stmt->execute();
  }catch (\PDOException $e) {
    echo json_encode(['error' => 'Ошибка БД'.$e->getMessage()]);
    exit;
  }
  $reviews = $stmt->fetchAll();
  if (!$reviews) {
    echo json_encode(['error' => 'Ошибка БД']);
    exit;
  }

  http_response_code(200); // OK
  echo json_encode(['reviews' => $reviews]);
?>

