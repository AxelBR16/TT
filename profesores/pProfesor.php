<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión y si es un profesor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'profesor') {
    header("Location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor</title>
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/profesor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="p-3 barra_navegacion ">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="profesor.php" class="nav-link inicio--active">Inicio</a></li>
                    <li><a href="#" class="nav-link text-white">Trabajo Terminal</a></li>
                    <li><a href="#" class="nav-link text-white">Horarios</a></li>
                    <li><a href="#" class="nav-link text-white">Notificaciones</a></li>
                </ul>
                <div class="text-end">
                    <a href="../logout.php" type="button" class="btn botonP">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </header>
    <main class="content container mt-5">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Esta es la página del profesor.</p>
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
</div>
</body>
</html>
