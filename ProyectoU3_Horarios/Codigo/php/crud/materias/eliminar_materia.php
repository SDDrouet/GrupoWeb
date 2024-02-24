<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod_materia'])) {
    $cod_materia = $_POST['cod_materia'];

    $sql = "DELETE FROM materias WHERE cod_materia='$cod_materia'";

    if ($conn->query($sql) === TRUE) {
        header("Location: materias.php");
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}

$conn->close();
?>
