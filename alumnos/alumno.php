<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión y si es un alumno
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'alumno') {
    header("Location: ../index.php");
    exit();
}

// Verifica si las claves están definidas en la sesión y asigna un valor por defecto si no lo están
$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Nombre no disponible';
$user_lastname = isset($_SESSION['user_lastname']) ? htmlspecialchars($_SESSION['user_lastname']) : 'Apellido no disponible';
$user_boleta = isset($_SESSION['user_boleta']) ? htmlspecialchars($_SESSION['user_boleta']) : 'Boleta no disponible';
$user_email = isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'Correo no disponible';
// Configuración de la conexión a la base de datos (ejemplo)
$host = 'localhost';
$dbname = 'tt';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para verificar si el alumno está inscrito en algún trabajo terminal
    $query = "SELECT COUNT(*) AS count_tt FROM alumnos_trabajos WHERE Boleta = :Boleta";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Boleta', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar el resultado de la consulta
    $count_tt = $result['count_tt'];

    if ($count_tt > 0) {
        $mensaje_tt = "El alumno está inscrito en $count_tt trabajo terminal.";
    } else {
        $mensaje_tt = "El alumno no está inscrito en ningún trabajo terminal.";
    }

} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
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
</head>
<body>
<header class="p-3 barra_navegacion">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="alumno.php" class="nav-link px-2 text-white">Inicio</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Protocolo</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Notificaciones</a></li>
            </ul>
            <div class="text-end">
                <a href="../logout.php" type="button" class="btn btn-primary">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>
<main class="container mt-5">
    <h1>Bienvenido, <?php echo htmlspecialchars($user_name); ?></h1>
    <p>TT: <?php echo htmlspecialchars($mensaje_tt); ?></p>
    <h2>Datos Generales</h2>
    <p>Nombre: <?php echo htmlspecialchars($user_name); ?></p>
    <p>Apellido: <?php echo htmlspecialchars($user_lastname); ?></p>
    <p>Boleta: <?php echo htmlspecialchars($user_boleta); ?></p>
    <h2 class="mt-3">Contacto</h2>
    <p>Correo: <?php echo htmlspecialchars($user_email); ?></p>
    <h3 class="mt-3">Avisos CATT</h3>
    <p>
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit quia assumenda illum! Laborum temporibus ad, voluptas doloremque vel assumenda distinctio explicabo. Sunt officia possimus repellendus qui, maiores in laboriosam totam. Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga dolorem rem blanditiis aspernatur culpa corrupti veniam numquam, vero ducimus esse quidem tempore excepturi error reiciendis corporis aperiam. Tenetur, ipsam aspernatur.
        Eos delectus saepe repudiandae et tenetur eaque quas exercitationem, vero, tempore in pariatur. Asperiores vel nostrum voluptate accusantium minus amet, earum alias, quos aut sed veritatis sapiente, eveniet nam repellendus.
        Debitis blanditiis modi voluptates, ipsum fugiat molestias quisquam rem velit illo nesciunt atque voluptatibus deleniti excepturi exercitationem magni officiis aperiam! Odio porro quidem optio illum, maxime eveniet nesciunt id! Nesciunt!
        Accusantium deleniti nulla unde atque earum quis, velit laborum vero sequi, fugiat eum magnam deserunt ad. Ea vitae, quos neque totam harum omnis quam saepe. Soluta accusamus autem quaerat non?
    </p>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
