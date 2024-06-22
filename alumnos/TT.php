<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si es un alumno
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'alumno') {
    header("Location: ../index.php");
    exit();
}

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

// Obtener ID del trabajo terminal del alumno 
$id_trabajo = $_SESSION['id_trabajo_terminal']; 

// Consulta para obtener la URL del documento DOCX del trabajo terminal
$consulta = "SELECT urldocumento FROM trabajos_terminales WHERE id_trabajo = :id_trabajo";
$stmt = $con->prepare($consulta);

// Vincular el parámetro
$stmt->bindParam(':id_trabajo', $id_trabajo, PDO::PARAM_INT);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$url_documento = null;
if ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (array_key_exists('urldocumento', $fila)) {
        $url_documento = $fila['urldocumento'];
    } else {
        echo "La columna 'urldocumento' no existe en el resultado.";
        exit();
    }
} else {
    echo "No se encontró ningún resultado para el ID de trabajo terminal especificado.";
    header("Location: alumno.php");
    exit();
}

// Cerrar conexión a la base de datos
$con = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumno - Terminal Tracker</title>
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/alumno.css">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
<header class="p-3 barra_navegacion">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="alumno.php" class="nav-link text-white">Inicio</a></li>
                <li><a href="TT.php" class="nav-link inicio--active">Trabajo Terminal</a></li>
                <li><a href="horarios.php" class="nav-link text-white">Horarios</a></li>
                <li><a href="#" class="nav-link text-white">Notificaciones</a></li>
            </ul>
            <div class="text-end">
                <a href="../logout.php" type="button" class="text-white btn botonP">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>
<main class="content container mt-5 documento">
    <h1 class="text-center animate__animated animate__bounce">Visualización del Trabajo Terminal</h1>
    <div class="tt_container">
        <?php if ($url_documento): ?>
            <iframe src="<?php echo $url_documento; ?>" style="width:100%; height:100%;" frameborder="0"></iframe>
            <div class="text-center mt-3">
                
                <a onclick="agregarUrl()" class="btn btn-primary">Modificar URL</a>
            </div>
        <?php else: ?>
            <div class="text-center">
                <ion-icon name="add-circle-outline"  class="tt_container__add  animate__animated animate__rotateIn" onclick="agregarUrl()"></ion-icon>
            </div>
        <?php endif; ?>
    </div>
</main>
<div class="footer">
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top ba">
            <p class="col-md-4 mb-0 text-white">&copy; 2024 Terminal Tracker</p>
            <a href="administradores/administrador.php" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <p class="footer__logo text-white">Terminal Tracker</p>
            </a> 
        </footer>
    </div>
</div>
   <!-- SweetAlert -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (necesario para AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../js/aUrl.js"> </script>
</body>
</html>
