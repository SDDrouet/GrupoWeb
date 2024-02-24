<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_periodo'])) {
    $id_periodo = $_POST['id_periodo'];

    $sql = "DELETE FROM periodos WHERE id_periodo=$id_periodo";

    if ($conn->query($sql) === TRUE) {
        header("Location: periodos.php");
        echo "Periodo eliminado exitosamente";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}

$conn->close();
?>
