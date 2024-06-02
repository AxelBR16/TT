<?php
// request_reset.php

// Importa la clase Database 
require 'config/database.php';


// Crea una instancia de la clase Database
$db = new Database();

// Establece la conexión a la base de datos
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Generar un token
    function generateToken($length = 50) {
        return bin2hex(random_bytes($length));
    }

    // Almacenar el token en la base de datos
    function storeToken($email, $token, $con) {
        $stmt = $con->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->bindValue(2, $token, PDO::PARAM_STR);
        $stmt->execute();

    }

    $token = generateToken();
    storeToken($email, $token, $con);

    // Enviar el correo electrónico
    function sendResetEmail($email, $token) {
        $resetLink = "http://localhost/TT/TT%20Rama%20Axel/reset_password.php?token=" . $token;
        $subject = "Solicitud de restablecimiento de contraseña";
        $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $resetLink;
        $headers = "From: no-reply@yourwebsite.com";

        mail($email, $subject, $message, $headers);
    }

    sendResetEmail($email, $token);
    echo "Se ha enviado un enlace de recuperación a tu correo electrónico. ".$email;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Internas -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Recuperación de Contraseña</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" crossorigin="anonymous" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="login position-absolute top-50 start-50 translate-middle">
        <div class="login__titulo">
            <h1>Terminal Tracker</h1>
        </div>
        <h2 class="text-center h5 mb-3  fw-normal">Recuperación de Contraseña</h2>
        <form class="mt-5" method="POST" action="request_reset.php">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Correo" required>
            <div class="mt-5 login__boton">
                <a href="index.php">Regresar</a>
                <button class="btn btn-primary w-40 py-2" type="submit">Enviar</button>
            </div>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
