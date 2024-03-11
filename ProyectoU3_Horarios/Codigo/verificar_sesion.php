<?php
date_default_timezone_set('America/Guayaquil');
session_start();
if (!isset($_SESSION['userID'])) {
    $_SESSION['error_message'] = "Debe iniciar sesión primero.";
    header('Location: index.html'); // Redirige al inicio de sesión
    exit();
}
?>