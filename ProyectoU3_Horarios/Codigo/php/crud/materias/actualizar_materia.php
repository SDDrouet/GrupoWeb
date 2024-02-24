<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod_materia'])) {
    $cod_materia = $_POST['cod_materia'];
    $nombre_materia = $_POST['nombre_materia'];
    $departamento = $_POST['departamento'];
    $horas_semana = $_POST['horas_semana'];

    $sql = "UPDATE materias SET nombre_materia='$nombre_materia', departamento='$departamento', horas_semana=$horas_semana WHERE cod_materia='$cod_materia'";

    if ($conn->query($sql) === TRUE) {
        header("Location: materias.php");
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

$conn->close();
?>
