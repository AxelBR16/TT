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

    $query_get_sinodales = "SELECT nEmpleado FROM sinodales_trabajos WHERE id_trabajo = :id_trabajo";
    $stmt_get_sinodales = $con->prepare($query_get_sinodales);
    $stmt_get_sinodales->bindParam(':id_trabajo', $id_trabajo);
    $stmt_get_sinodales->execute();
    $sinodales = $stmt_get_sinodales->fetchAll(PDO::FETCH_COLUMN);

    // Obtener los IDs de los profesores (directores y sinodales) antes de eliminar las referencias
    $query_get_directores = "SELECT nEmpleado FROM directores_trabajos WHERE id_trabajo = :id_trabajo";
    $stmt_get_directores = $con->prepare($query_get_directores);
    $stmt_get_directores->bindParam(':id_trabajo', $id_trabajo);
    $stmt_get_directores->execute();
    $directores = $stmt_get_directores->fetchAll(PDO::FETCH_COLUMN);

    // Insertar notificaciones para los profesores (directores y sinodales)
    $mensaje_profesores = "La URL del documento del Trabajo Terminal $id_trabajo ha sido actualizada.";

    if (!empty($directores)) {
        $query_insert_notif_directores = "INSERT INTO notificaciones_profesores (nEmpleado, mensaje) VALUES (:nEmpleado, :mensaje)";
        $stmt_insert_notif_directores = $con->prepare($query_insert_notif_directores);

        foreach ($directores as $nEmpleado) {
            $stmt_insert_notif_directores->bindParam(':nEmpleado', $nEmpleado);
            $stmt_insert_notif_directores->bindParam(':mensaje', $mensaje_profesores);
            $stmt_insert_notif_directores->execute();
        }
    }

    if (!empty($sinodales)) {
        $query_insert_notif_sinodales = "INSERT INTO notificaciones_profesores (nEmpleado, mensaje) VALUES (:nEmpleado, :mensaje)";
        $stmt_insert_notif_sinodales = $con->prepare($query_insert_notif_sinodales);

        foreach ($sinodales as $nEmpleado) {
            $stmt_insert_notif_sinodales->bindParam(':nEmpleado', $nEmpleado);
            $stmt_insert_notif_sinodales->bindParam(':mensaje', $mensaje_profesores);
            $stmt_insert_notif_sinodales->execute();
        }
    }


    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    // Cerrar conexión a la base de datos
    $con = null;
}
?>
