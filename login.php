<?php
// Inicia la sesión
session_start();


// Importa la clase Database
require 'config/database.php';


// Crea una instancia de la clase Database
$db = new Database();


// Establece la conexión a la base de datos
$con = $db->conectar();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Función para verificar las credenciales en una tabla específica
    function verifyUser($con, $table, $email, $password) {
        $stmt = $con->prepare("SELECT * FROM $table WHERE Correo = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['Password'])) {
            return $user;
        }
        return false;
    }


    // Verificar si el usuario es alumno
    $user = verifyUser($con, 'alumnos', $email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['Boleta'];
        $_SESSION['user_name'] = $user['Nombre'];
        $_SESSION['user_role'] = 'alumno';
        header("Location: alumnos/alumno.php");
        exit();
    }


    // Verificar si el usuario es profesor
    $user = verifyUser($con, 'profesores', $email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['nEmpleado'];
        $_SESSION['user_name'] = $user['Nombre'];
        $_SESSION['user_role'] = 'profesor';
        header("Location: profesores/profesor.php");
        exit();
    }


    // Verificar si el usuario es administrador
    $user = verifyUser($con, 'administradores', $email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['NumeroEmpleado'];
        $_SESSION['user_name'] = $user['Nombre'];
        $_SESSION['user_role'] = 'administrador';
        header("Location: administradores/administrador.php");
        exit();
    }


    // Si no se encontró al usuario en ninguna tabla
    $error = "Correo o contraseña incorrectos.";
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
