<?php
require '../vendor/autoload.php';
require '../config/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Conectar a DB
$database = new Database();
$conn = $database->getConnection();

// Leer JSON del body
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    http_response_code(400);
    echo json_encode(["message" => "Request mal formado o sin JSON"]);
    exit;
}

// Campos requeridos: idprofesor (código de tarjeta), nombre, apellido, email, password
$idprofesor = trim($data->idprofesor ?? '');
$nombre     = trim($data->nombre ?? '');
$apellido   = trim($data->apellido ?? '');
$email      = trim($data->email ?? '');
$password   = $data->password ?? '';

if (empty($idprofesor) || empty($nombre) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan datos obligatorios (idprofesor, nombre, email, password)"]);
    exit;
}

// Verificar si ya existe idprofesor o email
$checkSql = "SELECT idprofesor, email FROM profesor WHERE idprofesor = :idprofesor OR email = :email LIMIT 1";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bindParam(':idprofesor', $idprofesor);
$checkStmt->bindParam(':email', $email);
$checkStmt->execute();
$exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

if ($exists) {
    // Determinar cuál existe y responder 409
    if ($exists['idprofesor'] === $idprofesor) {
        http_response_code(409);
        echo json_encode(["message" => "El idprofesor ya está registrado"]);
    } else {
        http_response_code(409);
        echo json_encode(["message" => "El email ya está registrado"]);
    }
    exit;
}

// Hashear contraseña con bcrypt
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insertar nuevo profesor
$insertSql = "INSERT INTO profesor (idprofesor, nombre, apellido, email, password) 
              VALUES (:idprofesor, :nombre, :apellido, :email, :password)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bindParam(':idprofesor', $idprofesor);
$insertStmt->bindParam(':nombre', $nombre);
$insertStmt->bindParam(':apellido', $apellido);
$insertStmt->bindParam(':email', $email);
$insertStmt->bindParam(':password', $hashedPassword);

if ($insertStmt->execute()) {
    http_response_code(201);
    echo json_encode([
        "message" => "Profesor registrado correctamente",
        "idprofesor" => $idprofesor
    ]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Error al registrar profesor"]);
}
?>
