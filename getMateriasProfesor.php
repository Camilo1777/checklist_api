<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Responder al preflight (OPTIONS) del navegador y salir
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require 'vendor/autoload.php';
require 'config/database.php';
require 'config/secret.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ------------------------
// 1. Leer headers
// ------------------------
$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if (empty($authHeader) || !preg_match('/Bearer\s+(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Token no proporcionado"]);
    exit;
}

$token = $matches[1];

// ------------------------
// 2. Decodificar token
// ------------------------
try {
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
    $idprofesor = $decoded->data->idprofesor ?? $decoded->data->id ?? null;

    if (!$idprofesor) {
        throw new Exception("ID de profesor no encontrado en token");
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Token inválido o expirado",
        "error" => $e->getMessage()
    ]);
    exit;
}

// ------------------------
// 3. Conexión DB
// ------------------------
try {
    // Database::getConnection() es el método definido en config/database.php
    $db = (new Database())->getConnection();
    if (!$db) {
        throw new Exception('No se pudo establecer conexión a la base de datos');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión a la base de datos.",
        "error" => $e->getMessage()
    ]);
    exit;
}

// ------------------------
// 4. Consulta materias por profesor
// ------------------------
try {
    // La tabla `materias` en este schema contiene `idmateria` y `nombremateria`.
    // Evitamos columnas inexistentes (p.ej. m.semestre, m.horas) que causaban el error 1054.
    $sql = "
        SELECT 
            m.idmateria,
            m.nombremateria AS materia,
            a.idasignacion
        FROM asignacion a
        INNER JOIN materias m ON a.idmateria = m.idmateria
        WHERE a.idprofesor = :idprofesor
    ";

    $stmt = $db->prepare($sql);
    // Forzamos tipo entero al bind para evitar problemas de tipo
    $stmt->bindValue(':idprofesor', (int)$idprofesor, PDO::PARAM_INT);
    $stmt->execute();
    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "materias" => $materias
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar las materias.",
        "error" => $e->getMessage()
    ]);
}
?>
