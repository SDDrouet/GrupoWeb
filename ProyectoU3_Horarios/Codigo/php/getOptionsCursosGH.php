<?php
include 'config.php';

// Obtener el ID del período desde la solicitud GET
$idPeriodo = $_GET['idPeriodo'];

// Realizar la consulta SQL para obtener los cursos relacionados con el ID del período
$query = "SELECT CONCAT(c.nrc, ' | ', m.nombre_materia, ' | ', IF(c.id_docente = 0, 'No asignado', CONCAT(u.nombre,' ', u.apellido))) AS curso FROM cursos c
INNER JOIN materias m ON c.id_materia = m.id_materia
LEFT JOIN docentes d ON c.id_docente = d.id_docente
LEFT JOIN usuarios u ON d.id_usuario = u.id_usuario
WHERE c.periodos_id_periodo = $idPeriodo"; // Filtrar por ID del período

$result = mysqli_query($link, $query);

$options = array();

// Recorrer los resultados y crear opciones para el select
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = $row['curso'];
}

// Devolver las opciones en formato JSON
echo json_encode($options);

mysqli_close($link);
?>