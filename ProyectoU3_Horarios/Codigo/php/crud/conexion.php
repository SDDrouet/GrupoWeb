<?php
$servername = "localhost";  // Cambia esto si tu servidor de base de datos es diferente
$username = "prueba";
$password = "prueba";
$database = "horarios";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
