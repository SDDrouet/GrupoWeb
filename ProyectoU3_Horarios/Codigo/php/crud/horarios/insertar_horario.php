<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = $_POST['dia'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    $sql = "INSERT INTO horarios (dia, hora_inicio, hora_fin)
            VALUES ('$dia', '$hora_inicio', '$hora_fin')";

    if ($conn->query($sql) === TRUE) {
        header("Location: horarios.php");
        echo "Horario agregado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
