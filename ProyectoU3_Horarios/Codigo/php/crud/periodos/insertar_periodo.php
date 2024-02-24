<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_periodo = $_POST['nombre_periodo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $sql = "INSERT INTO periodos (nombre_periodo, fecha_inicio, fecha_fin)
            VALUES ('$nombre_periodo', '$fecha_inicio', '$fecha_fin')";

    if ($conn->query($sql) === TRUE) {
        header("Location: periodos.php");
        echo "Periodo agregado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
