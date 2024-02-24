<?php
include '../conexion.php';

// Mostrar todas las aulas
$sql = "SELECT * FROM aulas";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID Aula: " . $row["id_aula"]. " - Capacidad: " . $row["capacidad"]. " - Bloque: " . $row["bloque"]. " - Observación: " . $row["observacion"]. " ";
        echo "<form action='eliminar_aula.php' method='post'>";
        echo "<input type='hidden' name='id_aula' value='". $row["id_aula"]. "'>";
        echo "<input type='submit' value='Eliminar'>";
        echo "</form>";

        echo "<form action='editar_aula.php' method='post'>";
        echo "<input type='hidden' name='id_aula' value='". $row["id_aula"]. "'>";
        echo "<input type='hidden' name='capacidad' value='". $row["capacidad"]. "'>";
        echo "<input type='hidden' name='bloque' value='". $row["bloque"]. "'>";
        echo "<input type='hidden' name='observacion' value='". $row["observacion"]. "'>";
        echo "<input type='submit' value='Editar'>";
        echo "</form>";

        echo "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
