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

   // Consulta para obtener el ID del trabajo terminal en el que el alumno está inscrito
   $query = "SELECT id_trabajo FROM alumnos_trabajos WHERE Boleta = :Boleta LIMIT 1";
   $stmt = $pdo->prepare($query);
   $stmt->bindParam(':Boleta', $_SESSION['user_id']);
   $stmt->execute();
   $result = $stmt->fetch(PDO::FETCH_ASSOC);

   // Verificar el resultado de la consulta
   if ($result) {
    $_SESSION['id_trabajo_terminal'] = $result['id_trabajo'];
       $trabajoID = htmlspecialchars($result['id_trabajo']);
       $mensaje_tt = "Estas inscrito en el trabajo terminal con ID: $trabajoID.";
   } else {
       $_SESSION['error'] = "No estás en ningún TT";
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
    <title>Alumno</title>
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/alumno.css">
     <!--  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" crossorigin="anonymous" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
                <li><a href="alumno.php" class="nav-link inicio--active">Inicio</a></li>
                <li><a href="TT.php" class="nav-link text-white">Trabajo Terminal</a></li>
                <li><a href="horarios.php" class="nav-link text-white">Horarios</a></li>
                <li><a href="#" class="nav-link text-white">Notificaciones</a></li>
            </ul>
            <div class="text-end">
                <a href="../logout.php" type="button" class=" text-white btn botonP">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>
<main class="mt-5">
    <div class="inicio content container">
        <?php
        echo '<div class="mb-3 inicio_id">';
        echo '<h1 class="animate__animated animate__heartBeat">Bienvenid@, ' . $user_name . '</h1>';
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
            } else {
                ?>
                <p class="fs-5"><b><?php echo htmlspecialchars($mensaje_tt); ?></b></p>
            <?php
                }
            echo '</div>';
            ?>
         </div>
    <div class="s">
        <div class="content container datosG"> 
            <div>
                <div class="datosG__informacion">
                    <ion-icon class="ico" name="person-outline"></ion-icon>
                    <p class="datosG__informacion--active"><?php echo htmlspecialchars($user_name); ?> <?php echo htmlspecialchars($user_lastname); ?></p>
                </div>
                <div class="datosG__informacion">
                    <ion-icon class="ico" name="id-card-outline"></ion-icon>
                    <p class="datosG__informacion--active"><?php echo htmlspecialchars($user_boleta); ?></p>
                </div>
                <div class="datosG__informacion">
                    <ion-icon class="ico" name="mail-outline"></ion-icon>
                    <p class="datosG__informacion--active"><?php echo htmlspecialchars($user_email); ?></p>
                </div>
            </div>   
            <div class="datosG__titulo">
                <h2 class="mt-2">Datos Generales</h2>
            </div>   
        </div> 
    </div>
    <div class=" container avisos">
        <div class="datosG__informacion">
            <ion-icon class="icoAviso" name="megaphone-outline"></ion-icon>
            <h3 class="mt-3">Avisos CATT</h3>
        </div>
        <div>
            <p>
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit quia assumenda illum! Laborum temporibus ad, voluptas doloremque vel assumenda distinctio explicabo. Sunt officia possimus repellendus qui, maiores in laboriosam totam. Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga dolorem rem blanditiis aspernatur culpa corrupti veniam numquam, vero ducimus esse quidem tempore excepturi error reiciendis corporis aperiam. Tenetur, ipsam aspernatur.
                Eos delectus saepe repudiandae et tenetur eaque quas exercitationem, vero, tempore in pariatur. Asperiores vel nostrum voluptate accusantium minus amet, earum alias, quos aut sed veritatis sapiente, eveniet nam repellendus.
                Debitis blanditiis modi voluptates, ipsum fugiat molestias quisquam rem velit illo nesciunt atque voluptatibus deleniti excepturi exercitationem magni officiis aperiam! Odio porro quidem optio illum, maxime eveniet nesciunt id! Nesciunt!
                Accusantium deleniti nulla unde atque earum quis, velit laborum vero sequi, fugiat eum magnam deserunt ad. Ea vitae, quos neque totam harum omnis quam saepe. Soluta accusamus autem quaerat non?
            </p>
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
</body>
</html>
