<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Tracker</title>
    <!-- Internas -->
    <link rel="stylesheet" href="css/principal.css">
    <link rel="stylesheet" href="css/normalize.css">
    <!-- Font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poetsen+One&display=swap" rel="stylesheet">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" crossorigin="anonymous" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="conte">
    
<div class="d-flex justify-content-center">
    <main class="login__registro">
        <div class="login__titulo">
            <h1>Terminal Tracker</h1>
        </div>
        <?php
            $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
        ?>
        <form method="POST" action="rProfesorBD.php"  id="registroForm" onsubmit="return validateForm()">
            <h1 class="text-center h5 mb-3 fw-normal">Registro en Terminal Tracker <b>Profesor</b></h1>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                    <div class="invalid-feedback">
                        Valid first name is required.
                    </div>
                </div>
    
                <div class="col-sm-6">
                    <label for="lastName" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                    <div class="invalid-feedback">
                        Valid last name is required.
                    </div>
                </div>
            </div>
            <div class="mt-2 form-floating">
                <input type="text" class="form-control" id="nEmpleado" name="nEmpleado" placeholder="nEmpleado" required>
                <label for="nEmpleado">Numero de Empleado</label>
            </div>

            <div class="mt-2 form-floating">
                <input type="email" class="form-control" id="correo" name="correo" placeholder="correo" value="<?php echo $email; ?>" readonly>
                <label for="correo">Correo</label>
            </div>
            <div class="mt-2 form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                <label for="password">Contraseña</label>
            </div>
            <div class="mt-2 form-floating">
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Password" required>
                <label for="confirmPassword">Confirma Contraseña</label>
            </div>
            
    
            <div class="mt-4 login__boton">
                <button class="btn btn-primary w-100 py-2" type="submit">Registrarse</button>
            </div>
            <p class="mt-3 text-body-secondary">&copy; 2024</p>
        </form>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/vPassword.js"></script>
</body>
</html>
