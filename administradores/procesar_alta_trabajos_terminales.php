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
$alumnos = array_map('trim', $alumnos); // Elimina espacios en blanco alrededor de cada boleta
$directores = explode(',', $_POST['directores']);
$sinodales = explode(',', $_POST['sinodales']);

// Iniciar una transacción
$con->beginTransaction();

try {
    // Verifica que Sean menos de 4 Alumnos
    if(count($alumnos) > 4){
        throw new Exception("Solo se permiten máximo 4 integrantes por trabajo terminal");
    }
    // Verifica que Sean menos de 2 Directores 
    if(count($directores) > 2){
        throw new Exception("Solo se permiten máximo 2 directores por trabajo terminal");
    }
    // Verifica que Sean 3 sinodales
      if(count($sinodales) != 3){
        throw new Exception("Deben ser 3 sinodales");
    }

    // Insertar el trabajo terminal
    $sql_insert_tt = "INSERT INTO TRABAJOS_TERMINALES (id_trabajo, titulo, direccion_almacenamiento) VALUES (?, ?, ?)";
    $stmt_insert_tt = $con->prepare($sql_insert_tt);
    $direccion_almacenamiento = "../TT/" . $id_trabajo;
    $stmt_insert_tt->execute([$id_trabajo, $titulo, $direccion_almacenamiento]);

    // Verificar y asignar alumnos al trabajo terminal
    $sql_select_alumno = "SELECT COUNT(*) FROM ALUMNOS WHERE Boleta = ?";
    $stmt_select_alumno = $con->prepare($sql_select_alumno);

    $sql_insert_alumno = "INSERT INTO ALUMNOS_TRABAJOS (Boleta, id_trabajo) VALUES (?, ?)";
    $stmt_insert_alumno = $con->prepare($sql_insert_alumno);

    foreach ($alumnos as $boleta) {
        $boleta = trim($boleta);

        // Verificar si la boleta del alumno existe en la base de datos
        $stmt_select_alumno->execute([$boleta]);
        if ($stmt_select_alumno->fetchColumn() == 0) {
            throw new Exception("Error: La boleta del alumno $boleta no existe en la base de datos.");
        }

        // Verificar si el alumno ya está asignado a otro trabajo terminal
        $sql_check_assignment = "SELECT COUNT(*) FROM ALUMNOS_TRABAJOS WHERE Boleta = ?";
        $stmt_check_assignment = $con->prepare($sql_check_assignment);
        $stmt_check_assignment->execute([$boleta]);
        if ($stmt_check_assignment->fetchColumn() > 0) {
            throw new Exception("Error: La boleta del alumno $boleta ya está asignada a otro trabajo terminal.");
        }

        // Insertar al alumno en la tabla ALUMNOS_TRABAJOS
        $stmt_insert_alumno->execute([$boleta, $id_trabajo]);
    }

    // Verificar y asignar directores al trabajo terminal
    $sql_select_director = "SELECT COUNT(*) FROM PROFESORES WHERE nEmpleado = ?";
    $stmt_select_director = $con->prepare($sql_select_director);

    $sql_insert_director = "INSERT INTO DIRECTORES_TRABAJOS (nEmpleado, id_trabajo) VALUES (?, ?)";
    $stmt_insert_director = $con->prepare($sql_insert_director);

    foreach ($directores as $nEmpleado) {
        $nEmpleado = trim($nEmpleado);

        // Verificar si el director existe en la base de datos
        $stmt_select_director->execute([$nEmpleado]);
        if ($stmt_select_director->fetchColumn() == 0) {
            throw new Exception("Error: El número de empleado del director $nEmpleado no existe en la base de datos.");
        }

        // Insertar al director en la tabla DIRECTORES_TRABAJOS
        $stmt_insert_director->execute([$nEmpleado, $id_trabajo]);
    }

    // Verificar y asignar sinodales al trabajo terminal
    $sql_select_sinodal = "SELECT COUNT(*) FROM PROFESORES WHERE nEmpleado = ?";
    $stmt_select_sinodal = $con->prepare($sql_select_sinodal);

    $sql_insert_sinodal = "INSERT INTO SINODALES_TRABAJOS (nEmpleado, id_trabajo) VALUES (?, ?)";
    $stmt_insert_sinodal = $con->prepare($sql_insert_sinodal);

    foreach ($sinodales as $nEmpleado) {
        $nEmpleado = trim($nEmpleado);

        // Verificar si el sinodal existe en la base de datos
        $stmt_select_sinodal->execute([$nEmpleado]);
        if ($stmt_select_sinodal->fetchColumn() == 0) {
            throw new Exception("Error: El número de empleado del sinodal $nEmpleado no existe en la base de datos.");
        }

        // Insertar al sinodal en la tabla SINODALES_TRABAJOS
        $stmt_insert_sinodal->execute([$nEmpleado, $id_trabajo]);
    }

    // Confirmar la transacción
    $con->commit();

    // Crear la carpeta dentro de "TT" con el ID del trabajo solo si no hubo errores
    if (!file_exists($direccion_almacenamiento)) {
        mkdir($direccion_almacenamiento, 0777, true);
    }

    $_SESSION['success'] = 'Trabajo terminal dado de alta exitosamente.';
} catch (PDOException $e) {
    // Deshacer la transacción en caso de error de PDO
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
