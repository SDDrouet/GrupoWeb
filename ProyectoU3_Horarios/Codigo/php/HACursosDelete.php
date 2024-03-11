<?php
include 'config.php';

// Obtener el ID del período desde la solicitud GET
$id_curso = $_POST['idCurso'];
$id_horario__aula = $_POST['idHorarioAula'];

$sql = "DELETE FROM horarios_aulas_cursos 
    WHERE id_curso = $id_curso
    AND id_horario__aula = $id_horario__aula";

if (mysqli_query($link, $sql)) {
    echo "Record deleted successfully";
} else {
    echo "Error deleted record periodos_docentes: " . mysqli_error($conn);
    exit();
}

$sql = "UPDATE horarios_aulas SET disponible = 1
        WHERE id_horario__aula = $id_horario__aula";

if (mysqli_query($link, $sql)) {
    echo "Record updated successfully";
} else {
    echo"Error updating record: " . mysqli_error($conn);
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
                        SET `horas_asignadas` = `horas_asignadas` + 2
                        WHERE `id_periodo_docente` = $id_periodo_docente";

if (mysqli_query($link, $sql)) {
    echo "Record deleted successfully";
} else {
    echo "Error deleted record periodos_docentes: " . mysqli_error($conn);
    exit();
}

echo $response;

mysqli_close($link);
?>