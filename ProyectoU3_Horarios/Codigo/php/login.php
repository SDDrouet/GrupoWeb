<?php
date_default_timezone_set('America/Guayaquil');

$host = "localhost";
$usuario = "admin";
$clave = "admin";
$nombre_base_datos = "PROYECTO_14768";

if(isset($_POST['btn_login'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$nombre_base_datos;charset=utf8mb4", $usuario, $clave);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $clave = $_POST['password'];
        
        // Consultar la base de datos para obtener el hash de la contraseña y el perfil del usuario
        $stmt = $pdo->prepare("SELECT clave, id_perfil FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $result = $stmt->fetch();
        
        if ($result && password_verify($clave, $result['clave'])) {
            session_start();
            
            $_SESSION['user_name'] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo '<script>alert("Usuario o contraseña incorrectos.");</script>';
            echo '<script>window.location.href = "../index.html";</script>';
            exit();
        }
    }
    
    $pdo = null;
}
?>