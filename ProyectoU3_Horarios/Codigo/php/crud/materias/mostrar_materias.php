<?php
include '../conexion.php';

// Mostrar todas las materias
$sql = "SELECT * FROM materias";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Código Materia: " . $row["cod_materia"]. " - Nombre: " . $row["nombre_materia"]. " - Departamento: " . $row["departamento"]. " - Horas Semana: " . $row["horas_semana"]. " ";
        echo "<form action='eliminar_materia.php' method='post'>";
        echo "<input type='hidden' name='cod_materia' value='". $row["cod_materia"]. "'>";
        echo "<input type='submit' value='Eliminar'>";
        echo "</form>";

        echo "<form action='editar_materia.php' method='post'>";
        echo "<input type='hidden' name='cod_materia' value='". $row["cod_materia"]. "'>";
        echo "<input type='hidden' name='nombre_materia' value='". $row["nombre_materia"]. "'>";
        echo "<input type='hidden' name='departamento' value='". $row["departamento"]. "'>";
        echo "<input type='hidden' name='horas_semana' value='". $row["horas_semana"]. "'>";
        echo "<input type='submit' value='Editar'>";
        echo "</form>";

        echo "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
