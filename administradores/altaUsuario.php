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
    <title>Alta Usuarios</title>
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
                        <a class="nav-link dropdown-toggle text-white inicio--active" href="#" data-bs-toggle="dropdown" aria-expanded="false">Usuarios</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="altaUsuario.php">Alta de Usuarios</a></li>
                            <li><a class="dropdown-item" href="bajaUsuario.php">Baja de Usuarios</a></li>
                        </ul>
                    </li>    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown" aria-expanded="false">Trabajos Terminales</a>
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
    <main class="contenido contenedor">
        <h1 class="mt-5 text-center">Alta de Usuarios</h1>
        <p class="fs-5">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi deserunt ipsum, repellat provident, ut sunt tenetur consectetur asperiores doloribus, quia molestias aliquid iusto dignissimos. Commodi dolorum expedita ratione voluptas velit?
        </p>
        <div class="mt-5">
            <div>
                <div>
                    <p class="fw-bolder fs-4 text-center">Formulario para registrar usuarios</p>
                    <!-- Formulario -->
                    <form class="justify-content-center" id="registrationForm">
                    <p class="fw-bolder fs-5">Seleccione el tipo de usuario a dar de alta</p>
                    <div>
                        <div class="radio-tile-group">
                            <div class="mr-4 input-container">
                                <input id="alumno" type="radio" name="role" value="alumno" checked>
                                <div class="radio-tile">
                                    <ion-icon name="walk"></ion-icon>
                                    <label for="alumno">Alumno</label>
                                </div>
                            </div>
                            <div class="input-container">
                                <input id="profesor" type="radio" name="role" value="profesor">
                                <div class="radio-tile">
                                    <ion-icon name="school-outline"></ion-icon>
                                    <label for="profesor">Profesor</label>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- Campo de Correo Electrónico -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
                        </div>
                        <!-- Campo de Confirmación de Correo Electrónico -->
                        <div class="mb-3">
                            <label for="confirmEmail" class="form-label">Confirmar Correo Electrónico</label>
                            <input type="email" class="form-control" id="confirmEmail" name="confirmEmail" placeholder="Confirme su correo electrónico" required>
                        </div>
                        <!-- Botón de Envío -->
                        <div class="botonI">
                            <button type="submit" class="btn btn-success btn-lg">Enviar correo de confirmación</button>
                        </div>
                    </form>
                    <div id="responseMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
    </main>
    <div class="footer">
        <div class="container">
            <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top ba">
                <p class="col-md-4 mb-0 text-white">&copy; 2024 Terminal Tracker</p>
                <a href="/administradores/administrador.php" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <p class="footer__logo text-white">Terminal Tracker</p>
                </a> 
            </footer>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/altaUsers.js"></script>
</body>
</html>
