<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_aula'])) {
    $id_aula = $_POST['id_aula'];
    $capacidad = $_POST['capacidad'];
    $bloque = $_POST['bloque'];
    $observacion = $_POST['observacion'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_aula'])) {
        $id_aula = $_POST['id_aula'];
        $capacidad = $_POST['capacidad'];
        $bloque = $_POST['bloque'];
        $observacion = $_POST['observacion'];
    
        echo "<h2>Editar Aula</h2>";
        echo "<form action='actualizar_aula.php' method='post'>";
        echo "ID Aula: <input type='text' name='id_aula' value='". $id_aula ."' readonly><br>";
        echo "Capacidad: <input type='number' name='capacidad' value='". $capacidad ."'><br>";
        echo "Bloque: <input type='text' name='bloque' value='". $bloque ."'><br>";
        echo "Observación: <input type='text' name='observacion' value='". $observacion ."'><br>";
        echo "<input type='submit' value='Actualizar'>";
        echo "</form>";
    }
}

$conn->close();
?>

