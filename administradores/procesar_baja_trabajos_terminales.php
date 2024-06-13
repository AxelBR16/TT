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
