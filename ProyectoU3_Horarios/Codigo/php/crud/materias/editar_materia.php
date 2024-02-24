<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod_materia'])) {
    $cod_materia = $_POST['cod_materia'];
    $nombre_materia = $_POST['nombre_materia'];
    $departamento = $_POST['departamento'];
    $horas_semana = $_POST['horas_semana'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod_materia'])) {
        $cod_materia = $_POST['cod_materia'];
        $nombre_materia = $_POST['nombre_materia'];
        $departamento = $_POST['departamento'];
        $horas_semana = $_POST['horas_semana'];
    
        echo "<h2>Editar Materia</h2>";
        echo "<form action='actualizar_materia.php' method='post'>";
        echo "Código Materia: <input type='text' name='cod_materia' value='". $cod_materia ."' readonly><br>";
        echo "Nombre Materia: <input type='text' name='nombre_materia' value='". $nombre_materia ."'><br>";
        echo "Departamento: <input type='text' name='departamento' value='". $departamento ."'><br>";
        echo "Horas Semana: <input type='number' name='horas_semana' value='". $horas_semana ."'><br>";
        echo "<input type='submit' value='Actualizar'>";
        echo "</form>";
    }
}

$conn->close();
?>
