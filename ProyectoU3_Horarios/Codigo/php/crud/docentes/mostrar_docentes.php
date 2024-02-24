<?php
include '../conexion.php';

// Mostrar todos los docentes
$sql = "SELECT * FROM docentes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id_docente"]. " - Nombres: " . $row["nombres"]. " - Apellidos: " . $row["apellidos"]. " - Horas Disponibles: " . $row["horas_disponibles"]. " ";
        echo "<form action='eliminar_docente.php' method='post'>";
        echo "<input type='hidden' name='id_docente' value='". $row["id_docente"]. "'>";
        echo "<input type='submit' value='Eliminar'>";
        echo "</form>";

        echo "<form action='editar_docente.php' method='post'>";
        echo "<input type='hidden' name='id_docente' value='". $row["id_docente"]. "'>";
        echo "<input type='hidden' name='nombres' value='". $row["nombres"]. "'>";
        echo "<input type='hidden' name='apellidos' value='". $row["apellidos"]. "'>";
        echo "<input type='hidden' name='horas_disponibles' value='". $row["horas_disponibles"]. "'>";
        echo "<input type='hidden' name='tipo_contrato' value='". $row["tipo_contrato"]. "'>";
        echo "<input type='hidden' name='correo' value='". $row["correo"]. "'>";
        echo "<input type='hidden' name='nivel_educacion' value='". $row["nivel_educacion"]. "'>";
        echo "<input type='hidden' name='especializacion' value='". $row["especializacion"]. "'>";
        echo "<input type='hidden' name='celular' value='". $row["celular"]. "'>";
        echo "<input type='hidden' name='cedula' value='". $row["cedula"]. "'>";
        echo "<input type='submit' value='Editar'>";
        echo "</form>";

        echo "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
