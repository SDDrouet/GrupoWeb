<?php
include 'config.php';

// Obtener el ID del período desde la solicitud GET
$id_cursoA = $_POST['idCursoA'];
$id_horario__aulaA = $_POST['idHorarioAulaA'];
$id_horario__aulaB = $_POST['idHorarioAulaB'];


$sql = "UPDATE horarios_aulas_cursos SET id_horario__aula = $id_horario__aulaB
        WHERE id_curso = $id_cursoA
        AND id_horario__aula = $id_horario__aulaA";

if (mysqli_query($link, $sql)) {
    echo "Record INSERT successfully";
} else {
    echo "Error INSERT record periodos_docentes: " . mysqli_error($conn);
    exit();
}

$sql = "UPDATE horarios_aulas SET disponible = 0
                WHERE id_horario__aula = $id_horario__aulaB
                ";

if (mysqli_query($link, $sql)) {
    echo "Record UPDATE successfully";
} else {
    echo "Error UPDATE record periodos_docentes: " . mysqli_error($conn);
    exit();
}


$sql = "UPDATE horarios_aulas SET disponible = 1
WHERE id_horario__aula = $id_horario__aulaA";

if (mysqli_query($link, $sql)) {
    echo "Record UPDATE successfully";
} else {
    echo "Error UPDATE record periodos_docentes: " . mysqli_error($conn);
    exit();
}

echo $response;

mysqli_close($link);
?>
