<?php
header('Access-Control-Allow-Origin: http://localhost:9000');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


//require __DIR__ . '..\..\..\..\vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require("..\Firebase\JWT.php");
require("..\Firebase\Key.php");

//$JWT = new JWT();
// Конфигурация подключения к БД
//$host = 'localhost';
//$dbname = 'your_database_name';
//$username = 'your_db_username';
//$password = 'your_db_password';

include("../../const.php");
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO("mysql:host=$dblocation;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpasswd, $options);
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Получаем JSON данные из POST запроса
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (!$data || !isset($data['user']) || !isset($data['login'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
  }

  $user = $data['user'];
  $login = $data['login'];

  // Проверяем существование логина <button class="citation-flag" data-index="8">
  $stmt = $pdo->prepare("SELECT id FROM users WHERE login = :login");
  $stmt->execute(['login' => $login]);
  if ($stmt->fetch()) {
    http_response_code(409); // Conflict
    echo json_encode(['error' => 'Login already exists']);
    exit;
  }

  // Хешируем пароль перед сохранением в БД
  $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);

  // Сохраняем пользователя в БД
  $stmt = $pdo->prepare("INSERT INTO users (login, name, email, password) VALUES (:login, :name, :email, :password)");
  $result = $stmt->execute([
    'login' => $login,
    'name' => $user['name'],
    'email' => $user['email'],
    'password' => $hashedPassword,
  ]);

  if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to register user']);
    exit;
  }

  // Генерируем JWT токен

  $payload = [
    "iss" => "http://news-analitika.org",
    "aud" => "http://news-analitika.com",
    "iat" => time(),
   // "nbf" => time() + 60,
    "exp" => time() + 3600,
    "data" => [
      "id" => $pdo->lastInsertId(),
      "login" => $login
    ]
  ];
  $jwt = JWT::encode($payload, $key,'HS256');
// Декодирование токена
//  try {
//    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
//    // Преобразование в массив
//    $decoded_array = (array)$decoded;
//    print_r($decoded_array);
//  } catch (\Exception $e) {
//    echo 'Error: ' . $e->getMessage();
//  }
  // Возвращаем токен клиенту
  http_response_code(201); // Created
  echo json_encode(['token' => $jwt]);
}
?>
