<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos."]);
    exit;
}

if (!isset($_GET['idprofesor'])) {
    echo json_encode(["success" => false, "message" => "Falta el parámetro idprofesor."]);
    exit;
}

$idprofesor = $_GET['idprofesor'];

try {
    $query = "
        SELECT m.idmateria, m.nombre AS materia, p.nombre AS profesor
        FROM materias m
        INNER JOIN asignacion a ON a.idmateria = m.idmateria
        INNER JOIN profesor p ON p.idprofesor = a.idprofesor
        WHERE p.idprofesor = :idprofesor
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idprofesor', $idprofesor);
    $stmt->execute();

    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "count" => count($materias),
        "materias" => $materias
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar las materias.",
        "error" => $e->getMessage()
    ]);
}
?>
