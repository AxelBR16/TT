<?php
session_start(); // Iniciar sesión para usar $_SESSION

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir archivos de PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Importar la clase Database
require 'config/database.php';

// Crear una instancia de la clase Database
$db = new Database();

// Establecer la conexión a la base de datos
$con = $db->conectar();

// Función para verificar si el correo electrónico está registrado en alguna de las tablas
function isEmailRegistered($email, $con) {
    // Tablas donde buscar el correo electrónico
    $tables = ['administradores', 'alumnos', 'profesores'];

    foreach ($tables as $table) {
        $stmt = $con->prepare("SELECT * FROM $table WHERE correo = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return true; // Si se encuentra el correo en alguna tabla, retornar true
        }
    }

    return false; // Si no se encuentra en ninguna tabla, retornar false
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verificar si el correo electrónico está registrado
    if (isEmailRegistered($email, $con)) {
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

        // Enviar el correo electrónico con PHPMailer
        function sendResetEmail($email, $token) {
            $resetLink = "http://localhost/TT/TT%20Rama%20Axel/reset_password.php?token=" . $token;
            $subject = "Solicitud de restablecimiento de contraseña";
            $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $resetLink;

            // Configuración de PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'soportecattescom@gmail.com'; // Tu dirección de correo electrónico de Gmail
            $mail->Password = 'tsna lqxg fnep umol'; // Contraseña de aplicación de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configuración del correo electrónico
            $mail->setFrom('soportecattescom@gmail.com', 'Terminal Tracker');
            $mail->addAddress($email); // Corregido aquí
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Enviar el correo electrónico
            $mail->send();
        }

        // Llamar a la función para enviar el correo electrónico
        sendResetEmail($email, $token);
        $_SESSION['success'] = "Se ha enviado un enlace de recuperación a tu correo electrónico. ".$email;
    } else {
        $_SESSION['error'] = "El correo electrónico proporcionado no está registrado en nuestra base de datos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Internas -->
    <link rel="stylesheet" href="css/principal.css">
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
<body class="conte">
    <main class="login position-absolute top-50 start-50 translate-middle">
        <div class="login__titulo">
            <h1>Terminal Tracker</h1>
        </div>
        <?php
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        
        if (isset($_SESSION['success'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito :)!',
                    text: '<?php echo $_SESSION['success']; ?>',
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>
            <?php
            unset($_SESSION['success']);
        } elseif (isset($_SESSION['error'])) {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo $_SESSION['error']; ?>',
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>
            <?php
            unset($_SESSION['error']);
        }
        ?>
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
