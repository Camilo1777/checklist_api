<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/secret.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verificarToken() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (empty($authHeader) || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Token faltante"]);
        exit;
    }

    $token = $matches[1];

    try {
        global $secret_key;
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

        if (!isset($decoded->data->idprofesor)) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Token sin idprofesor"]);
            exit;
        }

        // Retornamos el idprofesor contenido en el token
        return $decoded->data->idprofesor;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Token inválido o expirado"]);
        exit;
    }
}
?>
