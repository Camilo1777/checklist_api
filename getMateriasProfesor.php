<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/config/database.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos."]);
    exit;
}

if (!isset($_GET['idprofesor'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Falta el parámetro idprofesor."]);
    exit;
}

$idprofesor = $_GET['idprofesor'];

// Validación básica: asegurar que sea numérico
if (!is_numeric($idprofesor)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "El parámetro idprofesor debe ser numérico."]);
    exit;
}

try {
    $query = "
        SELECT m.idmateria, m.nombre AS materia, p.nombre AS profesor
        FROM materias m
        INNER JOIN asignacion a ON a.idmateria = m.idmateria
        INNER JOIN profesor p ON p.idprofesor = a.idprofesor
        WHERE p.idprofesor = :idprofesor
    ";

    $stmt = $conn->prepare($query);
    // Usar bindValue con tipo entero para mayor seguridad
    $stmt->bindValue(':idprofesor', (int)$idprofesor, PDO::PARAM_INT);
    $stmt->execute();

    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "count" => count($materias),
        "materias" => $materias
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    // En desarrollo está bien devolver el mensaje; en producción quitar $e->getMessage()
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar las materias.",
        "error" => $e->getMessage()
    ]);
}
?>
