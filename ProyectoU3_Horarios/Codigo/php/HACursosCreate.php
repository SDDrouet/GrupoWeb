<?php
include 'config.php';

// Obtener el ID del período desde la solicitud GET
$id_curso = $_POST['idCurso'];
$id_horario__aula = $_POST['idHorarioAula'];



$sql = "INSERT INTO horarios_aulas_cursos (id_horario__aula,id_curso) VALUES ($id_horario__aula, $id_curso)";

if (mysqli_query($link, $sql)) {
    $response = "Record INSERT successfully";
} else {
    echo "Error INSERT record periodos_docentes: " . mysqli_error($conn);
    exit();
}

$sql = "UPDATE horarios_aulas SET disponible = 0
                WHERE id_horario__aula = $id_horario__aula";

if (mysqli_query($link, $sql)) {
    echo "Record UPDATE successfully";
} else {
    echo "Error UPDATE record periodos_docentes: 1" . mysqli_error($conn);
    exit();
}

$sql = "SELECT id_periodo_docente
        FROM periodos_docentes pd
        JOIN cursos c ON c.id_docente = pd.id_docente AND c.periodos_id_periodo = pd.id_periodo
        WHERE c.id_curso = $id_curso;";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $id_periodo_docente = $row["id_periodo_docente"];
}

$sql = "UPDATE `periodos_docentes` 
        SET `horas_asignadas` = `horas_asignadas` - 2
        WHERE `id_periodo_docente` = $id_periodo_docente;";

if (mysqli_query($link, $sql)) {
    echo "Record UPDATE successfully finaaaaaal";
} else {
    echo "Error UPDATE record: 2 sql: $sql" . mysqli_error($conn);
    exit();
}

mysqli_close($link);
?>