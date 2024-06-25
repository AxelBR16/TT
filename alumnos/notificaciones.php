<?php
session_start();

// Verifica si el usuario ha iniciado sesión y si es un alumno
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'alumno') {
    header("Location: ../index.php");
    exit();
}

// Configuración de la conexión a la base de datos (ejemplo)
$host = 'localhost';
$dbname = 'tt';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener las notificaciones del alumno
    $query = "SELECT id, mensaje, fecha, leido FROM notificaciones_alumnos WHERE boleta = :Boleta ORDER BY fecha DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Boleta', $_SESSION['user_id']);
    $stmt->execute();
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/alumno.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
<header class="p-3 barra_navegacion ">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="alumno.php" class="nav-link text-white">Inicio</a></li>
                <li><a href="TT.php" class="nav-link text-white">Trabajo Terminal</a></li>
                <li><a href="horarios.php" class="nav-link text-white">Horarios</a></li>
                <li>
                    <a href="notificaciones.php" class="nav-link inicio--active">
                        Notificaciones 
                        <?php
                        // Consulta para obtener el número de notificaciones no leídas
                        $query_count = "SELECT COUNT(*) AS count FROM notificaciones_alumnos WHERE boleta = :Boleta AND leido = 0";
                        $stmt_count = $pdo->prepare($query_count);
                        $stmt_count->bindParam(':Boleta', $_SESSION['user_id']);
                        $stmt_count->execute();
                        $count_notificaciones = $stmt_count->fetchColumn();

                        if ($count_notificaciones > 0) {
                            echo "<span class='badge bg-danger'>$count_notificaciones</span>";
                        }
                        ?>
                    </a>
                </li>

            </ul>
            <div class="text-end">
                <a href="../logout.php" type="button" class=" text-white btn botonP">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>
<main class="mt-5 content">
    <div class="container mt-5">
        <h3>Notificaciones</h3>
        <div class="list-group">
            <?php
            if (isset($notificaciones) && count($notificaciones) > 0) {
                foreach ($notificaciones as $notificacion) {
                    // Verifica si existe la clave 'id' en $notificacion
                    if (isset($notificacion['id'])) {
                        $_SESSION['notificacion_id'] = $notificacion['id']; // Guardar el ID de notificación en la sesión
                        $mensaje = htmlspecialchars($notificacion['mensaje']);
                        $fecha = htmlspecialchars($notificacion['fecha']);
                        $leido = $notificacion['leido'] ? 'Leído' : 'No leído';
                        echo "<div class='list-group-item'>";
                        echo "<p><strong>Mensaje:</strong> $mensaje</p>";
                        echo "<p><strong>Fecha:</strong> $fecha</p>";
                        echo "<p><strong>Estado:</strong> $leido</p>";
                        // Botón para marcar como leído
                        if (!$notificacion['leido']) {
                            echo "<form action='marcar_leido.php' method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='notificacion_id' value='{$notificacion['id']}' />";
                            echo "<button type='submit' class='btn btn-sm btn-primary'>Marcar como leído</button>";
                            echo "</form>";
                        }
                        // Eliminar
                        echo "<form action='borrar_notificacion.php' method='post' style='display: inline;'>";
                        echo "<input type='hidden' name='notificacion_id' value='{$notificacion['id']}' />";
                        echo "<button type='submit' class='btn btn-sm btn-danger'>Eliminar mensaje</button>";
                        echo "</form>";

                        echo "</div>";
                    } else {
                        // Manejo de error si no existe 'id'
                        echo "<div class='list-group-item'>";
                        echo "<p>Error: ID de notificación no encontrado.</p>";
                        echo "</div>";
                    }
                }
            } else {
                echo "<p>No hay notificaciones.</p>";
            }            
            ?>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '" . $_SESSION['mensaje'] . "'
            });";
            unset($_SESSION['mensaje']);
        }
        if (isset($_SESSION['error'])) {
            echo "Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '" . $_SESSION['error'] . "'
            });";
            unset($_SESSION['error']);
        }
        ?>
    });
    </script>
</body>
</html>
