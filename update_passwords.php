<?php
// Inicia la sesión
session_start();

// Importa la clase Database 
require 'config/database.php';

// Crea una instancia de la clase Database
$db = new Database();
$con = $db->conectar();

// Función para actualizar las contraseñas en una tabla específica
function updatePasswords($con, $table, $idField) {
    // Selecciona todos los usuarios y sus contraseñas de la tabla especificada
    $stmt = $con->query("SELECT $idField, Password FROM $table");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        // Hash de la contraseña actual
        $hashedPassword = password_hash($user['Password'], PASSWORD_DEFAULT);
        
        // Actualiza la contraseña del usuario con el hash generado
        $updateStmt = $con->prepare("UPDATE $table SET Password = ? WHERE $idField = ?");
        $updateStmt->execute([$hashedPassword, $user[$idField]]);
    }

    echo "Passwords updated successfully in $table.<br>";
}

// Actualiza las contraseñas en las tablas alumnos, profesores y administradores
updatePasswords($con, 'alumnos', 'Boleta');
updatePasswords($con, 'profesores', 'NumeroEmpleado');
updatePasswords($con, 'administradores', 'NumeroEmpleado');
?>
