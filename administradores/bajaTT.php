<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión y si es un administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'administrador') {
    header("Location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baja TT</title>
    <!-- Internas -->
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/admi.css">
    <!-- Font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" crossorigin="anonymous" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
<body>
    <header class="p-3 barra_navegacion">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="administrador.php" class="nav-link px-2 text-white">Inicio</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown" aria-expanded="false">Usuarios</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="altaUsuario.php">Alta de Usuarios</a></li>
                            <li><a class="dropdown-item" href="bajaUsuario.php">Baja de Usuarios</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white inicio--active" href="#" data-bs-toggle="dropdown" aria-expanded="false">Trabajos Terminales</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="altaTT.php">Alta de trabajos terminales</a></li>
                            <li><a class="dropdown-item" href="bajaTT.php">Baja de trabajos terminales</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="text-end">
                    <a href="../logout.php" type="button" class="btn btn-primary">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </header>
        <main class="container contenido" >
            <h1 class="text-center">Dar de Baja Trabajos Terminales</h1>
    
            <p class="fs-5">
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Distinctio, quasi? Accusamus possimus ab eligendi unde tempora est et cumque aliquid eum non inventore sunt molestiae, error soluta reiciendis delectus laudantium.
                Nulla, natus dolores iure repellat et consequatur explicabo vel tenetur omnis, nesciunt autem quos. Cumque qui quaerat molestias ratione accusamus error, aut quam excepturi labore dolorem minus sequi. Incidunt, vel?
            </p>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                ?>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Error :(!',
                    text: '<?php echo $_SESSION['error']; ?>',
                    customClass: {
                        confirmButton: 'btn-custom'
                    }
                });
                </script>
                <?php
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                ?>
                <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito :)!',
                    text: '<?php echo $_SESSION['success']; ?>',
                    customClass: {
                        confirmButton: 'btn-custom'
                    }
                });
                </script>
                <?php
                unset($_SESSION['success']);
            }
            ?>
            <form action="procesar_baja_trabajos_terminales.php" method="post">
                    <div>
                        <label for="id_trabajo" class="form-label fs-5"><b>ID del Trabajo:</b></label>
                        <input type="text" class="form-control" id="id_trabajo" name="id_trabajo" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="c_id_trabajo" class="form-label fs-5"><b>Confirma ID del Trabajo:</b></label>
                        <input type="text" class="form-control" id="c_id_trabajo" name="c_id_trabajo" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
             
                <div class="botonI mt-4">
                    <button type="submit" class="btn btn-danger btn-lg">Dar de Baja</button>
                </div>
            </form>
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
    <?php echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>'; ?>
</body>
</html>
