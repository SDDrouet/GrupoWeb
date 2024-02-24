<?php
include '../conexion.php';

// Mostrar todos los periodos
$sql = "SELECT * FROM periodos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id_periodo"]. " - Nombre: " . $row["nombre_periodo"]. " - Inicio: " . $row["fecha_inicio"]. " - Fin: " . $row["fecha_fin"]. " ";
        echo "<form action='eliminar_periodo.php' method='post'>";
        echo "<input type='hidden' name='id_periodo' value='". $row["id_periodo"]. "'>";
        echo "<input type='submit' value='Eliminar'>";
        echo "</form>";
        echo "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
