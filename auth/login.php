<?php
// Archivos necesarios
require '../vendor/autoload.php';
require '../config/database.php';
require '../config/secret.php';

use Firebase\JWT\JWT;

// CORS básico para Flutter Web. En producción usa un origen fijo.
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: *");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Conexión a la DB
$database = new Database();
$conn = $database->getConnection();

// Leer JSON
$raw = file_get_contents('php://input');
if (empty($raw)) {
    http_response_code(400);
    echo json_encode(["message" => "Cuerpo vacío."]);
    exit;
}

$data = json_decode($raw);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["message" => "JSON inválido."]);
    exit;
}

$email = filter_var(trim($data->email ?? ''), FILTER_VALIDATE_EMAIL);
$password = $data->password ?? '';

if (!$email || empty($password)) {
    http_response_code(400);
    echo json_encode(["message" => "email o password faltantes o inválidos."]);
    exit;
}

try {
    $query = "SELECT idprofesor, nombre, email, password FROM profesor WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        http_response_code(404);
        echo json_encode(["message" => "Profesor no encontrado."]);
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(["message" => "Usuario o contraseña incorrectos."]);
        exit;
    }

    // Generar token
    $payload = [
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
        "data" => [
            "idprofesor" => $user['idprofesor'],
            "nombre" => $user['nombre'],
            "email" => $user['email']
        ]
    ];

    $token = JWT::encode($payload, $secret_key, 'HS256');

    http_response_code(200);
    echo json_encode([
        "message" => "Login correcto",
        "token" => $token,
        "profesor" => [
            "idprofesor" => $user['idprofesor'],
            "nombre" => $user['nombre'],
            "email" => $user['email']
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error interno."]);
}

?>
