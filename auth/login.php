<?php
// Incluir archivos necesarios
require '../vendor/autoload.php';
require '../config/database.php';
require '../config/secret.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Permitir peticiones desde la app Flutter (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Recibir datos JSON enviados desde Flutter
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $query = "SELECT * FROM profesor WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $data->email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña (bcrypt)
        if (password_verify($data->password, $user['password'])) {
            
            // Crear el token JWT
            $token = JWT::encode(
                [
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
                ],
                $secret_key,
                'HS256'
            );

            // Respuesta exitosa
            http_response_code(200);
            echo json_encode([
                "message" => "Login exitoso",
                "token" => $token,
                "profesor" => [
                    "idprofesor" => $user['idprofesor'],
                    "nombre" => $user['nombre'],
                    "email" => $user['email']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Contraseña incorrecta."]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "El profesor no existe."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Datos incompletos."]);
}
?>
