<?php
// Desactivar la salida de errores de PHP
ini_set('display_errors', 0);
error_reporting(0);

// Asegurarse de que se envíe un header JSON
header('Content-Type: application/json');

// Función para enviar una respuesta JSON y terminar la ejecución
function sendJsonResponse($success, $message = '') {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

// Capturar todos los errores y convertirlos en excepciones
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    session_start();
    require_once '../config/database.php';  // Asegúrate de que la ruta sea correcta

    // Verifica si el usuario ha iniciado sesión y si es un profesor
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'profesor') {
        sendJsonResponse(false, 'No autorizado');
    }

    // Obtiene los datos JSON del cuerpo de la solicitud
    $json_data = file_get_contents('php://input');
    $schedule_data = json_encode(json_decode($json_data));  // Re-codifica para asegurar un JSON válido

    $database = new Database();
    $conn = $database->conectar();

    // Prepara la consulta SQL para actualizar el horario del profesor
    $sql = "UPDATE profesores SET horarios = :horarios WHERE nEmpleado = :nEmpleado";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':horarios', $schedule_data, PDO::PARAM_STR);
    $stmt->bindParam(':nEmpleado', $_SESSION['user_id'], PDO::PARAM_INT);

    $result = $stmt->execute();

    if ($result) {
        sendJsonResponse(true, 'Horario guardado correctamente');
    } else {
        sendJsonResponse(false, 'Error al actualizar la base de datos');
    }

} catch(PDOException $e) {
    sendJsonResponse(false, 'Error de base de datos: ' . $e->getMessage());
} catch(Exception $e) {
    sendJsonResponse(false, 'Error inesperado: ' . $e->getMessage());
}
?>