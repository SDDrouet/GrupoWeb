<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_horario'])) {
    $id_horario = $_POST['id_horario'];

    $sql = "DELETE FROM horarios WHERE id_horario=$id_horario";

    if ($conn->query($sql) === TRUE) {
        header("Location: horarios.php");
        echo "Horario eliminado exitosamente";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}

$conn->close();
?>
