<?php
session_start();

// Verifica si el usuario ha iniciado sesión y si es un alumno
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'alumno') {
    header("Location: ../index.php");
    exit();
}

// Verifica si se ha enviado un ID de notificación
if (!isset($_POST['notificacion_id'])) {
    header("Location: notificaciones.php");
    exit();
}

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'tt';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID de la notificación
    $notificacion_id = $_POST['notificacion_id'];

    // Preparar la consulta para actualizar el estado de la notificación
    $query = "UPDATE notificaciones_alumnos SET leido = 1 WHERE id = :id AND boleta = :boleta";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $notificacion_id, PDO::PARAM_INT);
    $stmt->bindParam(':boleta', $_SESSION['user_id'], PDO::PARAM_STR);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir de vuelta a la página de notificaciones con un mensaje de éxito
        $_SESSION['mensaje'] = "Notificación marcada como leída.";
    } else {
        // Redirigir de vuelta a la página de notificaciones con un mensaje de error
        $_SESSION['error'] = "No se pudo marcar la notificación como leída.";
    }

} catch (PDOException $e) {
    // Manejar errores de base de datos
    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
}

// Redirigir de vuelta a la página de notificaciones
header("Location: notificaciones.php");
exit();
?>