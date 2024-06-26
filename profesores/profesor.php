<?php
session_start();

// Verifica si el usuario ha iniciado sesión y si es un profesor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'profesor') {
    header("Location: ../index.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include '../config/database.php';

// Crear una instancia de la clase Database y conectar a la base de datos
$db = new Database();
$conn = $db->conectar();

// Obtener el ID del profesor desde la sesión
$profesor_id = $_SESSION['user_id'];

// Consulta para obtener los trabajos terminales donde el profesor es sinodal
$sql1 = "
SELECT tt.id_trabajo, tt.titulo, tt.direccion_almacenamiento, tt.urlDocumento, 'sinodal' as rol
FROM trabajos_terminales tt
JOIN sinodales_trabajos st ON tt.id_trabajo = st.id_trabajo
WHERE st.nEmpleado = :profesor_id";


$stmt1 = $conn->prepare($sql1);
$stmt1->bindParam(':profesor_id', $profesor_id, PDO::PARAM_INT);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los trabajos terminales donde el profesor es director
$sql2 = "
SELECT tt.id_trabajo, tt.titulo, tt.direccion_almacenamiento, tt.urlDocumento, 'director' as rol
FROM trabajos_terminales tt
JOIN directores_trabajos dt ON tt.id_trabajo = dt.id_trabajo
WHERE dt.nEmpleado = :profesor_id";

$stmt2 = $conn->prepare($sql2);
$stmt2->bindParam(':profesor_id', $profesor_id, PDO::PARAM_INT);
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Combinar los resultados de ambas consultas
$result = array_merge($result1, $result2);
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
         <!--  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" crossorigin="anonymous" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
    <header class="p-3 barra_navegacion">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="profesor.php" class="nav-link inicio--active">Inicio</a></li>
                    <li><a href="horarios.php" class="nav-link text-white">Horario</a></li>
                    <li>
                        <a href="notificaciones.php" class="nav-link text-white">
                            Notificaciones 
                            <?php
                            // Consulta para obtener el número de notificaciones no leídas
                            $query_count = "SELECT COUNT(*) AS count FROM notificaciones_profesores WHERE nEmpleado = :nEmpleado AND leido = 0";
                            $stmt_count = $conn->prepare($query_count);
                            $stmt_count->bindParam(':nEmpleado', $_SESSION['user_id']);
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
                    <a href="../logout.php" type="button" class="btn botonP">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </header>
    <div class="content container mt-5">
        <h1>Bienvenid@, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p class="text-j">
            Esta es la página del profesor. Para poder visualizar el trabajo terminal favor de presionar en el link <b>Ver más</b> de cada trabajo terminal esto le abrirá el documento en una pestaña. Para agregar comentarios, seleccione el texto específico o posicione el cursor en el lugar donde desea dejar un comentario. Haga clic en el icono de comentario en la barra superior del documento (se asemeja a un cuadro de diálogo con un signo de más adentro).
        </p>

        <!-- Mostrar los trabajos terminales -->
        <h2>Trabajos Terminales</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Rol</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_trabajo']); ?></td>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($row['rol']); ?></td>
                    <td>
                        <?php if (!empty($row['urlDocumento'])): ?>
                            <a href="<?php echo htmlspecialchars($row['urlDocumento']); ?>" target="_blank" onclick="notificarVisualizacion('<?php echo htmlspecialchars($row['id_trabajo']); ?>', '<?php echo htmlspecialchars($_SESSION['user_name']); ?>')">Ver más</a>
                        <?php else: ?>
                            <span onclick="mostrarAlerta();">Ver más</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <div class="container">
            <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top ba">
                <p class="col-md-4 mb-0 text-white">&copy; 2024 Terminal Tracker</p>
                <a href="administradores/administrador.php" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <p class="footerMarca text-white">Terminal Tracker</p>
                </a>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/toast.js"></script>
    <script>
function notificarVisualizacion(id_trabajo, profesor_name) {
    fetch('notificar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id_trabajo: id_trabajo,
            profesor_name: profesor_name
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
        } else {
            console.log(data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
    </script>
</body>
</html>

<?php
// Cerrar la conexión
$conn = null;
?>
