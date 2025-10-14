<?php
require '../vendor/autoload.php';
require '../config/database.php';

// CORS para pruebas (Flutter Web). En entorno real, fija el origen.
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: *");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Conectar a la DB
$database = new Database();
$conn = $database->getConnection();

// Leer JSON
$raw = file_get_contents('php://input');
if (empty($raw)) {
    http_response_code(400);
    echo json_encode(["message" => "Cuerpo vacío." ]);
    exit;
}

$data = json_decode($raw);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["message" => "JSON inválido." ]);
    exit;
}

// Campos
$idprofesor = trim($data->idprofesor ?? '');
$nombre     = trim($data->nombre ?? '');
$apellido   = trim($data->apellido ?? '');
$email_raw  = trim($data->email ?? '');
$email      = filter_var($email_raw, FILTER_VALIDATE_EMAIL);
$password   = $data->password ?? '';

if (empty($idprofesor) || empty($nombre) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["message" => "Faltan datos obligatorios o email inválido." ]);
    exit;
}

try {
    // ¿Existe ya?
    $checkSql = "SELECT idprofesor, email FROM profesor WHERE idprofesor = :idprofesor OR email = :email LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(':idprofesor', $idprofesor);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();
    $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($exists) {
        if ($exists['idprofesor'] === $idprofesor) {
            http_response_code(409);
            echo json_encode(["message" => "Código ya registrado." ]);
        } else {
            http_response_code(409);
            echo json_encode(["message" => "Email ya registrado." ]);
        }
        exit;
    }

    // Guardar usuario
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
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
        echo json_encode(["message" => "Profesor creado.", "idprofesor" => $idprofesor ]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "No se pudo crear el profesor." ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error interno." ]);
}

?>
