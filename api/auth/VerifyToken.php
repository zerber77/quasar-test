<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require("Firebase\JWT.php");
require("Firebase\Key.php");
//include("../const.php");


$JWT = new JWT();
// Проверяем, является ли запрос preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("HTTP/1.1 204 No Content");
  exit();
}
//$Key = new Key();
// Получаем токен из заголовка Authorization
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
  http_response_code(401); // Unauthorized
  echo json_encode(['error' => 'Missing Authorization header']);
  exit;
}

$auth_header = $_SERVER['HTTP_AUTHORIZATION'];
if (strpos($auth_header, 'Bearer ') !== 0) {
  http_response_code(401); // Unauthorized
  echo json_encode(['error' => 'Invalid token format']);
  exit;
}

$jwt = substr($auth_header, 7); // Удаляем "Bearer " из начала строки

try {
  // Декодируем токен

  $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

  // Проверяем данные токена
  if ($decoded->exp < time()) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Token has expired']);
    exit;
  }

  // Если всё хорошо, продолжаем обработку запроса
  echo json_encode(['message' => 'Token is valid', 'data' => $decoded]);
} catch (\Exception $e) {
  http_response_code(401); // Unauthorized
  echo json_encode(['error' => 'Invalid token: ' . $e->getMessage()]);
}
