<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


//use Firebase\JWT\JWT;
//use Firebase\JWT\Key;

//require("../auth/Firebase/JWT.php");
//require("../auth/Firebase/Key.php");
// Конфигурация подключения к БД
include("../const.php");
require("../auth/VerifyToken.php");


$token = verifyToken($key);
if (array_key_exists('error', $token)) {
  echo json_encode ($token);
  exit;
}

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO("mysql:host=$db_analitika;dbname=$dbname_analitika;charset=utf8mb4", $dbuser_analitika, $dbpasswd_analitika, $options);
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Получаем JSON данные из POST запроса
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (!$data || !isset($data['login']) || !isset($data['name']) || !isset($data['review'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
  }

  $login = $data['login'];
  $name = $data['name'];
  $review = $data['review'];

  // Проверяем существование пользователя с указанным логином
  try {
    $stmt = $pdo->prepare("SELECT user_id, name, login FROM users WHERE login = :login AND name = :name");
    $stmt->execute(['login' => $login, 'name' =>$name]);
  }catch (\PDOException $e) {
    echo json_encode(['error' => 'Ошибка БД'.$e->getMessage()]);
    exit;
  }
  $user = $stmt->fetch();
  if (!$user) {
    echo json_encode(['error' => 'Такой пользователь не зарегистрированы в системе']);
    exit;
  }
  $user_id = $user['user_id'];
  // Сохраняем пользователя в БД
  $stmt = $pdo->prepare("INSERT INTO reviews (user_id, review) VALUES (:user_id, :review)");
  $result = $stmt->execute([
    'user_id' => $user_id,
    'review' => $review,
  ]);

  if (!$result) {
    echo json_encode(['error' => 'Ошибка на сервере, попробуйте еще раз или позже']);
    exit;
  }
  
  // Возвращаем токен клиенту
  http_response_code(200); // OK
  echo json_encode(['data' => 'OK']);
}
?>

