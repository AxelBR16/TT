<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si es un administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'administrador') {
    header("Location: ../index.php");
    exit();
}

// Incluir la configuración de la base de datos
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_trabajo = $_POST['id_trabajo'];
    $c_id_trabajo = $_POST['c_id_trabajo'];

    if ($c_id_trabajo != $id_trabajo) {
        $_SESSION['error'] = "Los IDs no coinciden. Favor de verificar.";
    } else {
        // Crear una instancia de la clase Database y conectar a la base de datos
        $database = new Database();
        $pdo = $database->conectar();

        try {
            // Verificar si el trabajo terminal está registrado y obtener la dirección de la carpeta
            $query_select = "SELECT direccion_almacenamiento FROM trabajos_terminales WHERE id_trabajo = :id_trabajo";
            $stmt_select = $pdo->prepare($query_select);
            $stmt_select->bindParam(':id_trabajo', $id_trabajo);
            $stmt_select->execute();
            $direccion_almacenamiento = $stmt_select->fetchColumn();

            if (!$direccion_almacenamiento) {
                $_SESSION['error'] = "Error: El trabajo terminal con ID $id_trabajo no está registrado en la base de datos.";
                header("Location: bajaTT.php");
                exit();
            }

            // Iniciar una transacción
            $pdo->beginTransaction();

            // Obtener las boletas de los alumnos asignados al trabajo terminal
            $query_get_alumnos = "SELECT boleta FROM alumnos_trabajos WHERE id_trabajo = :id_trabajo";
            $stmt_get_alumnos = $pdo->prepare($query_get_alumnos);
            $stmt_get_alumnos->bindParam(':id_trabajo', $id_trabajo);
            $stmt_get_alumnos->execute();
            $alumnos = $stmt_get_alumnos->fetchAll(PDO::FETCH_COLUMN);

            // Obtener los IDs de los profesores (directores y sinodales) antes de eliminar las referencias
            $query_get_directores = "SELECT nEmpleado FROM directores_trabajos WHERE id_trabajo = :id_trabajo";
            $stmt_get_directores = $pdo->prepare($query_get_directores);
            $stmt_get_directores->bindParam(':id_trabajo', $id_trabajo);
            $stmt_get_directores->execute();
            $directores = $stmt_get_directores->fetchAll(PDO::FETCH_COLUMN);

            $query_get_sinodales = "SELECT nEmpleado FROM sinodales_trabajos WHERE id_trabajo = :id_trabajo";
            $stmt_get_sinodales = $pdo->prepare($query_get_sinodales);
            $stmt_get_sinodales->bindParam(':id_trabajo', $id_trabajo);
            $stmt_get_sinodales->execute();
            $sinodales = $stmt_get_sinodales->fetchAll(PDO::FETCH_COLUMN);
            


            // Eliminar las referencias en la tabla alumnos_trabajos
            $query1 = "DELETE FROM alumnos_trabajos WHERE id_trabajo = :id_trabajo";
            $stmt1 = $pdo->prepare($query1);
            $stmt1->bindParam(':id_trabajo', $id_trabajo);
            $stmt1->execute();

            // Eliminar las referencias en la tabla sinodales_trabajos
            $query2 = "DELETE FROM sinodales_trabajos WHERE id_trabajo = :id_trabajo";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->bindParam(':id_trabajo', $id_trabajo);
            $stmt2->execute();

            // Eliminar las referencias en la tabla directores_trabajos
            $query3 = "DELETE FROM directores_trabajos WHERE id_trabajo = :id_trabajo";
            $stmt3 = $pdo->prepare($query3);
            $stmt3->bindParam(':id_trabajo', $id_trabajo);
            $stmt3->execute();

            // Eliminar el trabajo terminal
            $query4 = "DELETE FROM trabajos_terminales WHERE id_trabajo = :id_trabajo";
            $stmt4 = $pdo->prepare($query4);
            $stmt4->bindParam(':id_trabajo', $id_trabajo);
            $stmt4->execute();

            // Confirmar la transacción
            $pdo->commit();

            // Eliminar la carpeta del trabajo terminal
            if (file_exists($direccion_almacenamiento)) {
                // Utilizamos una función recursiva para eliminar directorios y su contenido
                function eliminarDirectorio($dir) {
                    $files = array_diff(scandir($dir), array('.', '..'));
                    foreach ($files as $file) {
                        (is_dir("$dir/$file")) ? eliminarDirectorio("$dir/$file") : unlink("$dir/$file");
                    }
                    return rmdir($dir);
                }

                if (!eliminarDirectorio($direccion_almacenamiento)) {
                    $_SESSION['error'] = "Error al eliminar la carpeta del trabajo terminal.";
                }
            }

            // Insertar notificaciones para los alumnos
            if (!empty($alumnos)) {
                $mensaje = "Tu Trabajo Terminal con ID $id_trabajo ha sido dado de baja.";
                $query_insert_notificacion = "INSERT INTO notificaciones_alumnos (boleta, mensaje) VALUES (:boleta, :mensaje)";
                $stmt_insert_notificacion = $pdo->prepare($query_insert_notificacion);

                foreach ($alumnos as $boleta) {
                    $stmt_insert_notificacion->bindParam(':boleta', $boleta);
                    $stmt_insert_notificacion->bindParam(':mensaje', $mensaje);
                    $stmt_insert_notificacion->execute();
                }
            }

            // Insertar notificaciones para los profesores (directores y sinodales)
            $mensaje_profesores = "El Trabajo Terminal con ID $id_trabajo, en el cual participabas, ha sido dado de baja.";

            if (!empty($directores)) {
                $query_insert_notif_directores = "INSERT INTO notificaciones_profesores (nEmpleado, mensaje) VALUES (:nEmpleado, :mensaje)";
                $stmt_insert_notif_directores = $pdo->prepare($query_insert_notif_directores);

                foreach ($directores as $nEmpleado) {
                    $stmt_insert_notif_directores->bindParam(':nEmpleado', $nEmpleado);
                    $stmt_insert_notif_directores->bindParam(':mensaje', $mensaje_profesores);
                    $stmt_insert_notif_directores->execute();
                }
            }

            if (!empty($sinodales)) {
                $query_insert_notif_sinodales = "INSERT INTO notificaciones_profesores (nEmpleado, mensaje) VALUES (:nEmpleado, :mensaje)";
                $stmt_insert_notif_sinodales = $pdo->prepare($query_insert_notif_sinodales);

                foreach ($sinodales as $nEmpleado) {
                    $stmt_insert_notif_sinodales->bindParam(':nEmpleado', $nEmpleado);
                    $stmt_insert_notif_sinodales->bindParam(':mensaje', $mensaje_profesores);
                    $stmt_insert_notif_sinodales->execute();
                }
            }

            $_SESSION['success'] = "Trabajo terminal eliminado exitosamente.";
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $pdo->rollBack();
            $_SESSION['error'] = "Error en la ejecución de la consulta: " . $e->getMessage();
        } finally {
            // Cerrar la conexión
            $pdo = null;

            header("Location: bajaTT.php");
            exit();
        }
    }
}
?>
