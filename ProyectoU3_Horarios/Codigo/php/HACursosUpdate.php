<?php
include 'config.php';

// Obtener el ID del período desde la solicitud POST
$id_cursoA = $_POST['idCursoA'];
$id_cursoB = $_POST['idCursoB'];
$id_horario__aulaA = $_POST['idHorarioAulaA'];
$id_horario__aulaB = $_POST['idHorarioAulaB'];

// Primero, obtenemos el ID de horarios_aulas_cursos para el curso A
$sql = "SELECT id_horarios_aulas_cursos FROM horarios_aulas_cursos
        WHERE id_curso = $id_cursoA
        AND id_horario__aula = $id_horario__aulaA;";

$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$id_horarios_aulas_cursosA = $row["id_horarios_aulas_cursos"];

// Intercambiamos los cursos
$sql = "UPDATE horarios_aulas_cursos 
        SET id_curso = CASE 
                            WHEN id_curso = $id_cursoA THEN $id_cursoB
                            ELSE $id_cursoA
                      END,
            id_horario__aula = CASE 
                                    WHEN id_horario__aula = $id_horario__aulaA THEN $id_horario__aulaB
                                    ELSE $id_horario__aulaA
                               END
        WHERE (id_curso = $id_cursoA OR id_curso = $id_cursoB)
        AND (id_horario__aula = $id_horario__aulaA OR id_horario__aula = $id_horario__aulaB);";

if (mysqli_query($link, $sql)) {
    echo "Registros actualizados con éxito";
} else {
    echo "Error al actualizar registros: " . mysqli_error($link);
}

mysqli_close($link);
?>
