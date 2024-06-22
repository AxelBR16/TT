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

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'tt';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener el ID del trabajo terminal en el que el alumno está inscrito
    $query_tt = "SELECT id_trabajo FROM alumnos_trabajos WHERE Boleta = :Boleta LIMIT 1";
    $stmt_tt = $pdo->prepare($query_tt);
    $stmt_tt->bindParam(':Boleta', $_SESSION['user_boleta']);
    $stmt_tt->execute();
    $result_tt = $stmt_tt->fetch(PDO::FETCH_ASSOC);

    // Verificar el resultado de la consulta
    if ($result_tt) {
        $_SESSION['id_trabajo_terminal'] = $result_tt['id_trabajo'];
        $trabajoID = htmlspecialchars($result_tt['id_trabajo']);
        $mensaje_tt = "Estás inscrito en el trabajo terminal con ID: $trabajoID.";
    } else {
        $_SESSION['error'] = "No estás en ningún TT";
    }

    // Consulta para obtener los profesores sinodales asociados al trabajo terminal
    $query_sinodales = "SELECT p.nombre, p.apellidos, p.horarios 
                        FROM profesores p
                        INNER JOIN sinodales_trabajos st ON p.nEmpleado = st.nEmpleado
                        WHERE st.id_trabajo = :id_trabajo";
    $stmt_sinodales = $pdo->prepare($query_sinodales);
    $stmt_sinodales->bindParam(':id_trabajo', $_SESSION['id_trabajo_terminal']);
    $stmt_sinodales->execute();
    $sinodales = $stmt_sinodales->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener los profesores directores asociados al trabajo terminal
    $query_directores = "SELECT p.nombre, p.apellidos, p.horarios 
                         FROM profesores p
                         INNER JOIN directores_trabajos dt ON p.nEmpleado = dt.nEmpleado
                         WHERE dt.id_trabajo = :id_trabajo";
    $stmt_directores = $pdo->prepare($query_directores);
    $stmt_directores->bindParam(':id_trabajo', $_SESSION['id_trabajo_terminal']);
    $stmt_directores->execute();
    $directores = $stmt_directores->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumno</title>
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/alumno.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" crossorigin="anonymous" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
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
                <li><a href="TT.php" class="nav-link text-white">Trabajo Terminal</a></li>
                <li><a href="horarios.php" class="nav-link inicio--active">Horarios</a></li>
                <li><a href="#" class="nav-link text-white">Notificaciones</a></li>
            </ul>
            <div class="text-end">
                <a href="../logout.php" type="button" class=" text-white btn botonP">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>
<main class="mt-5 container content">
    <!-- Horarios de Profesores Sinodales -->
    <div class="container">
        <div class="sinodales">
            <h2>Horarios de Profesores Sinodales</h2>
            <ul>
                <?php foreach ($sinodales as $sinodal): ?>
                    <li><?php echo $sinodal['nombre'] . ' ' . $sinodal['apellidos'] . ': ' . $sinodal['horarios']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!-- Horarios de Profesores Directores -->
    <div class="container">
        <div class="directores">
            <h2>Horarios de Profesores Directores</h2>
            <ul>
                <?php foreach ($directores as $director): ?>
                    <li><?php echo $director['nombre'] . ' ' . $director['apellidos'] . ': ' . $director['horarios']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</main>
<div class="footer">
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top ba">
            <p class="col-md-4 mb-0 text-white">&copy; 2024 Terminal Tracker</p>
            <a href="administradores/administrador.php" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <p class="footer__logo text-white fontIco">Terminal Tracker</p>
            </a> 
        </footer>
    </div>
</div>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jI9t0" crossorigin="anonymous"></script>
</body>
</html>
