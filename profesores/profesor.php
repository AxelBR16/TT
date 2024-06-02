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
    <title>Profesor - Terminal Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container mt-5">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Esta es la página del profesor.</p>
        <a href="../logout.php" class="btn btn-primary">Cerrar sesión</a>
    </main>
</body>
</html>
