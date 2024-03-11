<?php
session_start();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SISTEMA DE HORARIOS</title>
    <!-- Estilos de Bootstrap que es codigo responsive y visual ya escrito por otro. -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/fonts/boxicons.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../css/style_min.css" rel="stylesheet">

    <!-- esto es para que el navegador se de cuenta cuando estamos en un celuar o tableta-->
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, user-scalable=yes">
</head>

<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        
        <?php 
        // Verifica si el usuario ha iniciado sesión y tiene un perfil
            if (isset($_SESSION['id_perfil'])) {
                $perfil = $_SESSION['id_perfil'];
            
                // Muestra opciones según el perfil
                if ($perfil == 3) {
                    include('sidebar.php');
                } elseif ($perfil == 4) {
                    include('sidebar_docente.php');
                } elseif ($perfil == 5) {
                    include('sidebar_apoyo.php');
                }
            
            } else {
                header("Location: ../index.html");
                exit();
            }

        ?>
        <!-- End of Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include('navbar.php'); ?>
                <!-- End of Topbar -->