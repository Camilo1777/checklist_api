<?php
require '../vendor/autoload.php';
require '../config/secret.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Espera Authorization: Bearer <token>
$headers = apache_request_headers();
$auth = '';
if (isset($headers['Authorization'])) {
    $auth = $headers['Authorization'];
} elseif (isset($headers['authorization'])) {
    $auth = $headers['authorization'];
}

if (empty($auth) || !preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
    http_response_code(401);
    echo json_encode(["message" => "Token faltante"]);
    exit;
}

$token = $matches[1];

try {
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
    http_response_code(200);
    echo json_encode(["message" => "Token válido", "data" => $decoded->data]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Token inválido o expirado"]);
}
?>
