<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_aula = $_POST['id_aula'];
    $capacidad = $_POST['capacidad'];
    $bloque = $_POST['bloque'];
    $observacion = $_POST['observacion'];

    $sql = "INSERT INTO aulas (id_aula, capacidad, bloque, observacion)
            VALUES ('$id_aula', $capacidad, '$bloque', '$observacion')";

    if ($conn->query($sql) === TRUE) {
        header("Location: aulas.php");
        echo "Aula agregada exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
