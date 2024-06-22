<?php
session_start();

// Verifica si el usuario ha iniciado sesión y si es un profesor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'profesor') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario - Profesor</title>
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/profesor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
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
                    <li><a href="profesor.php" class="nav-link text-white">Inicio</a></li>
                    <li><a href="horarios.php" class="nav-link inicio--active">Horario</a></li>
                    <li><a href="#" class="nav-link text-white">Notificaciones</a></li>
                </ul>
                <div class="text-end">
                    <a href="../logout.php" type="button" class="btn botonP">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </header>
    <div class="content container mt-5">
        <h1 class="text-center mb-2">Horario Semanal</h1>
        <p class="mt-3 mb-5">Favor de poner solo las horas libres que tiene a lo largo de la semana marcando en la casilla la hora</p>
        <table class="table table-bordered">
    <thead>
        <tr>
            <th>Hora</th>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miércoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
        $horas = [
            '7:00 - 8:30', '8:30 - 10:00', '10:00 - 10:30', '10:30 - 12:00',
            '12:00 - 13:30', '13:30 - 15:00', '15:00 - 16:30', '16:30 - 18:00',
            '18:00 - 18:30', '18:30 - 20:00', '20:00 - 21:00'
        ];

        foreach ($horas as $index => $hora) {
            echo "<tr>";
            echo "<td>$hora</td>";
            foreach ($dias as $dia) {
                $id = strtolower(str_replace(' ', '', $dia)) . '_' . $index;
                echo "<td id='$id' onclick='highlightCell(this)'></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
        <div class="d-flex justify-content-center">
            <button onclick="saveSchedule()" class="btn botonP mt-3">Guardar Horario</button>
        </div>
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
        let selectedCells = [];

        function highlightCell(cell) {
            cell.classList.toggle('high');
            
            const cellId = cell.id;
            
            // Añadir o remover la celda de la lista de seleccionadas
            const index = selectedCells.indexOf(cellId);
            if (index > -1) {
                selectedCells.splice(index, 1);
            } else {
                selectedCells.push(cellId);
            }
        }
                function saveSchedule() {
    // Convertir el array a JSON para enviarlo al servidor
    const scheduleData = JSON.stringify(selectedCells);
    
    // Enviar los datos al servidor usando fetch
    fetch('save_schedule.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: scheduleData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Error en la respuesta del servidor: ' + text);
            });
        }
        return response.json();
    })
    .then(data => {
        if(data.success) {
            Swal.fire('Éxito', data.message || 'Horario guardado correctamente', 'success');
        } else {
            Swal.fire('Error', data.message || 'No se pudo guardar el horario', 'error');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        Swal.fire('Error', 'Ocurrió un error al guardar el horario. Por favor, intente de nuevo y revise la consola para más detalles.', 'error');
    });
}
    </script>
</body>
</html>