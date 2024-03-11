<?php
// Process delete operation after confirmation
if (isset($_POST["id_horarios_aulas_cursos"]) && !empty($_POST["id_horarios_aulas_cursos"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    $id_horarios_aulas_cursos = $_POST["id_horarios_aulas_cursos"];
    
    $sql = "SELECT id_horario__aula, id_curso FROM horarios_aulas_cursos WHERE id_horarios_aulas_cursos = $id_horarios_aulas_cursos";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $id_horario__aula = $row["id_horario__aula"];
            $id_curso = $row["id_curso"];
        }
    } else {
        echo "0 results";
    }

    // Prepare a delete statement
    $sql = "DELETE FROM horarios_aulas_cursos WHERE id_horarios_aulas_cursos = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Set parameters
        $param_id = trim($_POST["id_horarios_aulas_cursos"]);

        // Bind variables to the prepared statement as parameters
        if (is_int($param_id))
            $__vartype = "i";
        elseif (is_string($param_id))
            $__vartype = "s";
        elseif (is_numeric($param_id))
            $__vartype = "d";
        else
            $__vartype = "b"; // blob
        mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {

            
            $sql = "UPDATE horarios_aulas SET disponible = 1
            WHERE id_horario__aula = $id_horario__aula";

            if (mysqli_query($link, $sql)) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
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
            }

            // Records deleted successfully. Redirect to landing page
            header("location: horarios_aulas_cursos-index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter
    $_GET["id_horarios_aulas_cursos"] = trim($_GET["id_horarios_aulas_cursos"]);
    if (empty($_GET["id_horarios_aulas_cursos"])) {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Eliminar Registro</title>
</head>
<?php include('header.php'); ?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h1>Eliminar Registro</h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="alert alert-danger fade-in">
                        <input type="hidden" name="id_horarios_aulas_cursos" value="<?php echo trim($_GET["id_horarios_aulas_cursos"]); ?>" />
                        <p>¿Está Seguro que quiere eliminar el registro?</p><br>
                        <p>
                            <input type="submit" value="Eliminar" class="btn btn-danger">
                            <a href="horarios_aulas_cursos-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>