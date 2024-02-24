<?php
include '../conexion.php';

// Mostrar todos los horarios
$sql = "SELECT * FROM horarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id_horario"]. " - Día: " . $row["dia"]. " - Hora Inicio: " . $row["hora_inicio"]. " - Hora Fin: " . $row["hora_fin"]. " ";
        echo "<form action='eliminar_horario.php' method='post'>";
        echo "<input type='hidden' name='id_horario' value='". $row["id_horario"]. "'>";
        echo "<input type='submit' value='Eliminar'>";
        echo "</form>";
        echo "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
