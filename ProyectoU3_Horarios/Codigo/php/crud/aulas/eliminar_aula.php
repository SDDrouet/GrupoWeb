<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_aula'])) {
    $id_aula = $_POST['id_aula'];

    $sql = "DELETE FROM aulas WHERE id_aula='$id_aula'";

    if ($conn->query($sql) === TRUE) {
        header("Location: aulas.php");
        echo "Aula eliminada exitosamente";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}

$conn->close();
?>
