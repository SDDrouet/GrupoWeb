<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_aula'])) {
    $id_aula = $_POST['id_aula'];
    $capacidad = $_POST['capacidad'];
    $bloque = $_POST['bloque'];
    $observacion = $_POST['observacion'];

    $sql = "UPDATE aulas SET capacidad=$capacidad, bloque='$bloque', observacion='$observacion' WHERE id_aula='$id_aula'";

    if ($conn->query($sql) === TRUE) {
        header("Location: aulas.php");
        echo "Aula actualizada exitosamente";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

$conn->close();
?>
