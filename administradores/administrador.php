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
    <title>Administrador - Terminal Tracker</title>
    <!-- Internas -->
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/admi.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="p-3 barra_navegacion">
            <div class="container">
              <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                  <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                </a>
        
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white " href="#" data-bs-toggle="dropdown" aria-expanded="false">Usuarios</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Alta de Usuarios</a></li>
                            <li><a class="dropdown-item" href="#">Baja de Usuarios</a></li>
                        </ul>
                    </li>    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown" aria-expanded="false">Trabajos Terminales</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Alta de trabajos terminales</a></li>
                        <li><a class="dropdown-item" href="#">Baja de trabajos terminales</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="text-end">
                  <a href="../logout.php" type="button" class="btn btn-primary">Cerrar sesión</a>
                </div>
              </div>
            </div>
          </header>
          <main class="mt-5 contenedor">
            <div class="inicio">
                <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                <img class="inicio__img img-thumbnail"  src="../img/administrador/escudoESCOM.png" alt="escudoESCOM">
            </div>
            <p class="mt-5">Esta es la página del administrador.</p>
            <h3>Seccion para avisos</h3>
            <p class="text-justify">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perspiciatis dolor voluptatem, iste laborum unde voluptatum. Maiores, perspiciatis laborum totam repellat dolor commodi deserunt velit adipisci quas in animi qui perferendis!
            Sunt modi possimus autem molestias ullam labore sequi? Cupiditate quaerat aut earum? Architecto ex quo veniam qui corrupti aliquid laudantium adipisci necessitatibus. Distinctio inventore eum harum illum, repudiandae corporis libero.
            Voluptates similique enim sit porro dolores, laudantium, ex corporis eius fugiat ea voluptatum nostrum quas praesentium odit possimus quasi et quam, explicabo tenetur deleniti numquam incidunt asperiores blanditiis! Perspiciatis, deserunt.
            Maiores magnam rerum excepturi corporis iusto non, minus incidunt, placeat iste architecto quidem dicta illo modi quibusdam fugiat quam perferendis labore dolores! Quasi nihil, aliquam debitis corporis nostrum libero quia?
            Quam tenetur, nesciunt dolorum suscipit ipsum beatae quae iusto! Neque cumque alias laboriosam explicabo dicta vel excepturi provident, blanditiis atque voluptates exercitationem, mollitia, quae beatae fuga? Quam odio beatae doloremque.
            Dolorum, recusandae illum! Error,</p>
          </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
