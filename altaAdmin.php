<?php
// Importa la clase Database
require 'config/database.php';

// Crea una instancia de la clase Database
$db = new Database();

// Establece la conexión a la base de datos utilizando PDO
$con = $db->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica y obtiene datos del formulario
    $firstName = isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '';
    $numeroEmpleado = isset($_POST['numeroEmpleado']) ? htmlspecialchars($_POST['numeroEmpleado']) : '';
    $correo = isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Verifica que todos los campos requeridos estén presentes
    if (empty($firstName) || empty($lastName) || empty($numeroEmpleado) || empty($correo) || empty($password)) {
        echo "Por favor completa todos los campos.";
        exit();
    }

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Preparar la consulta SQL con parámetros
    $sql = "INSERT INTO administradores (NumeroEmpleado, Nombre, Apellidos, Correo, Password)
            VALUES (:numeroEmpleado, :firstName, :lastName, :correo, :password)";
    $stmt = $con->prepare($sql);

    // Asignar valores a los parámetros
    $stmt->bindParam(':numeroEmpleado', $numeroEmpleado);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':password', $hashed_password);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Mostrar alerta de registro exitoso
        echo "<script>alert('Registro exitoso');</script>";
        
        // Redirigir a la página de inicio de sesión después de mostrar la alerta
        echo "<script>window.location.href = 'index.php';</script>";
        exit(); // Asegura que el script se detenga después de la redirección
    } else {
        echo "<script>alert('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Administradores</title>
</head>
<body>
    <h2>Registro de Administradores</h2>
    <form method="POST" action="">
        <label for="numeroEmpleado">Número de Empleado:</label>
        <input type="text" id="numeroEmpleado" name="numeroEmpleado" required><br><br>
        
        <label for="firstName">Nombre:</label>
        <input type="text" id="firstName" name="firstName" required><br><br>
        
        <label for="lastName">Apellidos:</label>
        <input type="text" id="lastName" name="lastName" required><br><br>
        
        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required><br><br>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Registrar">
    </form>
</body>
</html>
