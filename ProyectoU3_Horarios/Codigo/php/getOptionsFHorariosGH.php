<?php
include 'config.php';

// Obtener el ID del período desde la solicitud GET
$idPeriodo = $_GET['idPeriodo'];
$idAula = $_GET['idAula'];

// Realizar la consulta SQL para obtener los cursos relacionados con el ID del período
$query = "SELECT DISTINCT ha.id_horario__aula AS id_horario, h.dia, h.hora_inicio
FROM horarios h
JOIN periodos_horarios ph ON ph.id_horario = h.id_horario
JOIN horarios_aulas ha ON ha.id_horario = h.id_horario
WHERE ha.id_periodo = $idPeriodo
AND ha.id_aula = $idAula
ORDER BY h.dia ASC, h.hora_inicio ASC;"; // Filtrar por ID del período

$result = mysqli_query($link, $query);

$id_horario = array();
$dia = array();
$hora_inicio = array();

// Recorrer los resultados y crear opciones para el select
while ($row = mysqli_fetch_assoc($result)) {
    $id_horario[] = $row['id_horario'];
    $dia[] = $row['dia'];
    $hora_inicio[] = $row['hora_inicio'];
}

// Crear un array asociativo con los arrays de cursos y horarios
$response = array(
    'id_horario' => $id_horario,
    'dia' => $dia,
    'hora_inicio' => $hora_inicio
);

// Devolver la respuesta en formato JSON
echo json_encode($response);

mysqli_close($link);
?>