<?php
// deleteUser.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $idNumber = $_POST['idNumber'];
    $confirmIdNumber = $_POST['confirmIdNumber'];

    // Check if the ID numbers match
    if ($idNumber !== $confirmIdNumber) {
        echo json_encode(['status' => 'error', 'message' => 'Los números de identificación no coinciden.']);
        exit;
    }

    require '../config/database.php';

    $db = new Database();

    $con = $db->conectar();

    if ($role === 'alumno') {
        $table = 'alumnos';
        $column = 'Boleta';
    } else {
        $table = 'profesores';
        $column = 'nEmpleado';
    }

    try {
        error_log("Role: $role");
        error_log("ID Number: $idNumber");
        error_log("Table: $table");
        error_log("Column: $column");

        $sql = "DELETE FROM $table WHERE $column = :idNumber";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':idNumber', $idNumber, PDO::PARAM_STR);

        if ($stmt->execute()) {
            error_log("Affected Rows: " . $stmt->rowCount());

            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontró el usuario.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }

    $con = null; 

}
?>
