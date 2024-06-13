<?php
session_start();

// Importa la clase Database
require '../config/database.php';

// Crea una instancia de la clase Database
$db = new Database();

// Establece la conexión a la base de datos
$con = $db->conectar();

// Verificar conexión
if (!$con) {
    die("Conexión fallida");
}

// Obtener datos del formulario
$id_trabajo = $_POST['id_trabajo'];
$titulo = $_POST['titulo'];
$alumnos = explode(',', $_POST['alumnos']);
$directores = explode(',', $_POST['directores']);
$sinodales = explode(',', $_POST['sinodales']);

// Iniciar una transacción
$con->beginTransaction();

try {
    // Insertar el trabajo terminal
    $sql = "INSERT INTO TRABAJOS_TERMINALES (id_trabajo, titulo, direccion_almacenamiento) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $direccion_almacenamiento = "../TT/" . $id_trabajo;
    $stmt->execute([$id_trabajo, $titulo, $direccion_almacenamiento]);

    // Verificar y asignar alumnos al trabajo terminal
    $sql = "SELECT COUNT(*) FROM ALUMNOS WHERE Boleta = ?";
    $stmt = $con->prepare($sql);
    foreach ($alumnos as $boleta) {
        $boleta = trim($boleta);
        $stmt->execute([$boleta]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Error: La boleta del alumno $boleta no existe en la base de datos.");
        }
    }

    $sql = "INSERT INTO ALUMNOS_TRABAJOS (Boleta, id_trabajo) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    foreach ($alumnos as $boleta) {
        $boleta = trim($boleta);
        $stmt->execute([$boleta, $id_trabajo]);
    }

    // Verificar y asignar directores al trabajo terminal
    $sql = "SELECT COUNT(*) FROM PROFESORES WHERE nEmpleado = ?";
    $stmt = $con->prepare($sql);
    foreach ($directores as $nEmpleado) {
        $nEmpleado = trim($nEmpleado);
        $stmt->execute([$nEmpleado]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Error: El número de empleado del director $nEmpleado no existe en la base de datos.");
        }
    }

    $sql = "INSERT INTO DIRECTORES_TRABAJOS (nEmpleado, id_trabajo) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    foreach ($directores as $nEmpleado) {
        $nEmpleado = trim($nEmpleado);
        $stmt->execute([$nEmpleado, $id_trabajo]);
    }

    // Verificar y asignar sinodales al trabajo terminal
    $sql = "SELECT COUNT(*) FROM PROFESORES WHERE nEmpleado = ?";
    $stmt = $con->prepare($sql);
    foreach ($sinodales as $nEmpleado) {
        $nEmpleado = trim($nEmpleado);
        $stmt->execute([$nEmpleado]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Error: El número de empleado del sinodal $nEmpleado no existe en la base de datos.");
        }
    }

    $sql = "INSERT INTO SINODALES_TRABAJOS (nEmpleado, id_trabajo) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    foreach ($sinodales as $nEmpleado) {
        $nEmpleado = trim($nEmpleado);
        $stmt->execute([$nEmpleado, $id_trabajo]);
    }

    // Confirmar la transacción
    $con->commit();

    // Crear la carpeta dentro de "TT" con el ID del trabajo solo si no hubo errores
    if (!file_exists($direccion_almacenamiento)) {
        mkdir($direccion_almacenamiento, 0777, true);
    }

    $_SESSION['success'] = 'Trabajo terminal dado de alta exitosamente.';
} catch (PDOException $e) {
    // Deshacer la transacción en caso de error
    $con->rollBack();

    // Verificar si el error es por entrada duplicada
    if ($e->getCode() == 23000) {
        $_SESSION['error'] = 'Error: El ID del trabajo terminal ya está en uso.';
    } else {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
} catch (Exception $e) {
    // Deshacer la transacción en caso de error personalizado
    $con->rollBack();
    $_SESSION['error'] = $e->getMessage();
} finally {
    // Cerrar la conexión
    $con = null;
}

// Redirigir al formulario
header('Location: altaTT.php');
exit();
?>
