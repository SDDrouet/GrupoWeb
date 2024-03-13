<?php
include 'config.php';

// Obtener el ID del período desde la solicitud GET
$idPeriodo = $_GET['idPeriodo'];
$idDocente = $_GET['idDocente'];

// Realizar la consulta SQL para obtener los cursos relacionados con el ID del período
$query = "SELECT DISTINCT ha.id_horario__aula, h.dia, h.hora_inicio, c.nrc, m.nombre_materia, a.cod_aula FROM cursos c
JOIN materias m ON m.id_materia = c.id_materia
JOIN horarios_aulas_cursos hac ON hac.id_curso = c.id_curso
JOIN horarios_aulas ha ON hac.id_horario__aula = ha.id_horario__aula
JOIN horarios h ON h.id_horario = ha.id_horario
JOIN aulas a ON a.id_aula = ha.id_aula
WHERE c.periodos_id_periodo = $idPeriodo
AND c.id_docente = $idDocente;"; // Filtrar por ID del período

$result = mysqli_query($link, $query);

$id_horario = array();
$dia = array();
$hora_inicio = array();
$nrc = array();
$nombre_materia = array();
$cod_aula = array();

// Recorrer los resultados y crear opciones para el select
while ($row = mysqli_fetch_assoc($result)) {
    $id_horario[] = $row['id_horario__aula'];
    $dia[] = $row['dia'];
    $hora_inicio[] = $row['hora_inicio'];
    $nrc[] = $row['nrc'];
    $nombre_materia[] = $row['nombre_materia'];
    $cod_aula[] = $row['cod_aula'];
}

// Crear un array asociativo con los arrays de cursos y horarios
$response = array(
    'id_horario' => $id_horario,
    'dia' => $dia,
    'nrc' => $nrc,
    'nombre_materia' => $nombre_materia,
    'cod_aula' => $cod_aula,
    'hora_inicio' => $hora_inicio
);

// Devolver la respuesta en formato JSON
echo json_encode($response);

mysqli_close($link);
?>