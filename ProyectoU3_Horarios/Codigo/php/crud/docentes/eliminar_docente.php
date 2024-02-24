<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_docente'])) {
    $id_docente = $_POST['id_docente'];

    $sql = "DELETE FROM docentes WHERE id_docente='$id_docente'";

    if ($conn->query($sql) === TRUE) {
        header("Location: docentes.php");
        echo "Docente eliminado exitosamente";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}

$conn->close();
?>
