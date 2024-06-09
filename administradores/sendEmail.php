<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir archivos de PHPMailer
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $confirmEmail = filter_var($_POST['confirmEmail'], FILTER_SANITIZE_EMAIL);
    $role = $_POST['role']; 

    if ($email !== $confirmEmail) {
        echo json_encode(["status" => "error", "message" => "Los correos electrónicos no coinciden."]);
        exit;
    }

    $registrationLinkAlumno = "http://localhost/TT/TT%20Rama%20Axel/registroAlumno.php?email=" . urlencode($email);
    $registrationLinkProfesor = "http://localhost/TT/TT%20Rama%20Axel/registroProfesor.php?email=" . urlencode($email);


    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'soportecattescom@gmail.com'; // Correo de Gmail
        $mail->Password = 'tsna lqxg fnep umol'; // Contraseña de aplicación de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('soportecattescom@gmail.com', 'Terminal Tracker');

        if ($role === 'alumno') {
            // Envío para alumnos
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Registro en Terminal Tracker (Alumno)';
            $mail->Body    = "Haz clic en el siguiente enlace para completar tu registro como alumno: <a href='$registrationLinkAlumno'>$registrationLinkAlumno</a>";
        } elseif ($role === 'profesor') {
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Registro en Terminal Tracker (Profesor)';
            $mail->Body    = "Haz clic en el siguiente enlace para completar tu registro como profesor: <a href='$registrationLinkProfesor'>$registrationLinkProfesor</a>";
        } else {
            // En caso de que el rol no esté definido correctamente
            echo json_encode(["status" => "error", "message" => "Rol de usuario no válido."]);
            exit;
        }

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Correo de confirmación enviado."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error al enviar el correo. Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>
