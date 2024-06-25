<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

header('Content-Type: application/json');

// Verificar si el usuario está logueado y es un profesor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'profesor') {
    http_response_code(403);
    echo json_encode(["error" => "No autorizado"]);
    exit();
}

// Incluir la conexión a la base de datos
require_once '../config/database.php';

try {
    // Crear una instancia de Database y conectar a la base de datos
    $db = new Database();
    $conn = $db->conectar();
    
    // Obtener los datos de la solicitud AJAX
    $data = json_decode(file_get_contents("php://input"), true);
    $id_trabajo = $data['id_trabajo'];
    $profesor_name = $data['profesor_name'];

    // Registrar los datos recibidos para depuración
    error_log("Datos recibidos - id_trabajo: $id_trabajo, profesor_name: $profesor_name");

    // Obtener la boleta del alumno asociado al trabajo terminal
    $sql = "SELECT a.boleta 
            FROM trabajos_terminales tt
            JOIN alumnos_trabajos at ON tt.id_trabajo = at.id_trabajo
            JOIN alumnos a ON at.boleta = a.boleta
            WHERE tt.id_trabajo = :id_trabajo
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_trabajo', $id_trabajo, PDO::PARAM_STR);
    $stmt->execute();
    $boleta = $stmt->fetchColumn();

    // Registrar el resultado de la consulta para depuración
    error_log("Consulta ejecutada. Boleta encontrada: " . ($boleta ? $boleta : "No encontrada"));

    if ($boleta) {
        // Insertar una notificación
        $mensaje = "Profesor/a $profesor_name ha visualizado tu trabajo terminal";
        $sql = "INSERT INTO notificaciones_alumnos (boleta, mensaje) VALUES (:boleta, :mensaje)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':boleta', $boleta, PDO::PARAM_STR);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Notificación insertada correctamente"]);
        } else {
            throw new Exception("Error al insertar la notificación");
        }
    } else {
        throw new Exception("No se encontró la boleta para el trabajo terminal con ID: $id_trabajo");
    }
} catch (Exception $e) {
    error_log("Error en notificar.php: " . $e->getMessage());
    echo json_encode(["error" => $e->getMessage()]);
}

// Cerrar la conexión
$conn = null;
?>