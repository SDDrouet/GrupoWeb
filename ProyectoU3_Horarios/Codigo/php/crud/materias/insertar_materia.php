<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_materia = $_POST['cod_materia'];
    $nombre_materia = $_POST['nombre_materia'];
    $departamento = $_POST['departamento'];
    $horas_semana = $_POST['horas_semana'];

    $sql = "INSERT INTO materias (cod_materia, nombre_materia, departamento, horas_semana)
            VALUES ('$cod_materia', '$nombre_materia', '$departamento', $horas_semana)";

    if ($conn->query($sql) === TRUE) {
        header("Location: materias.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
