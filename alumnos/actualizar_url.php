<?php
session_start();
require '../config/database.php';

// Verificar si el usuario ha iniciado sesión y si es un alumno
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'alumno') {
    header("Location: ../index.php");
    exit();
}

// Verificar si se ha enviado la URL
if (isset($_POST['url_documento'])) {
    $url_documento = $_POST['url_documento'];
    $id_trabajo = $_SESSION['id_trabajo_terminal'];

    // Validar la URL
    if (!filter_var($url_documento, FILTER_VALIDATE_URL)) {
        echo "URL no válida";
        exit();
    }

    // Actualizar la URL en la base de datos
    $db = new Database();
    $con = $db->conectar();
    $consulta = "UPDATE trabajos_terminales SET urldocumento = :url_documento WHERE id_trabajo = :id_trabajo";
    $stmt = $con->prepare($consulta);
    $stmt->bindParam(':url_documento', $url_documento, PDO::PARAM_STR);
    $stmt->bindParam(':id_trabajo', $id_trabajo, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    // Cerrar conexión a la base de datos
    $con = null;
}
?>
