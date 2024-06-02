<?php

class Database {
    private $hostname = "localhost";
    private $database = "tt";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    // Cambié "chatset" a "charset" para corregir un error tipográfico

    public function conectar() {
        try {
            // Utiliza comillas simples para los valores del DSN
            $conexion = "mysql:host={$this->hostname};dbname={$this->database};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            // Usa un nombre de variable más claro
            $pdo = new PDO($conexion, $this->username, $this->password, $options);

            return $pdo;
        } catch(PDOException $e) {
            // En lugar de imprimir el mensaje de error, podrías registrar o manejar de otra manera el error
            echo 'Error de conexión: ' . $e->getMessage();
            exit(); // Puedes querer manejar el error de una manera más elegante en lugar de salir inmediatamente
        }
    }
}
?>
