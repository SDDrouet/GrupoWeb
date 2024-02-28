<?php
// Process delete operation after confirmation
if (isset($_POST["id_curso"]) && !empty($_POST["id_curso"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    $id_curso = $_POST["id_curso"];

    $sql = "SELECT periodos_id_periodo, id_aula, horarios_id_horario, id_docente FROM cursos WHERE id_curso = $id_curso";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $periodos_id_periodo = $row["periodos_id_periodo"];
            $id_aula = $row["id_aula"];
            $horarios_id_horario = $row["horarios_id_horario"];
            $id_docente = $row["id_docente"];
        }
    } else {
        echo "0 results";
    }


    // Prepare a delete statement
    $sql = "DELETE FROM cursos WHERE id_curso = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Set parameters
        $param_id = trim($_POST["id_curso"]);

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
            WHERE id_periodo = $periodos_id_periodo
            AND id_aula = '$id_aula'
            AND id_horario = $horarios_id_horario";

            if (mysqli_query($link, $sql)) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }

            $sql = "UPDATE `periodos_docentes` 
                        SET `horas_asignadas` = `horas_asignadas` + 2
                        WHERE `id_periodo` = $periodos_id_periodo 
                        AND `id_docente` = '$id_docente'";

            if (mysqli_query($link, $sql)) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleted record periodos_docentes: " . mysqli_error($conn);
            }

            // Records deleted successfully. Redirect to landing page
            header("location: cursos-index.php");
            exit();
        } else {
            $sql = "UPDATE horarios_aulas SET disponible = 0
            WHERE id_periodo = $periodos_id_periodo
            AND id_aula = '$id_aula'
            AND id_horario = $horarios_id_horario";

            if (mysqli_query($link, $sql)) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleted record horarios_aulas: " . mysqli_error($conn);
            }

            echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter
    $_GET["id_curso"] = trim($_GET["id_curso"]);
    if (empty($_GET["id_curso"])) {
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
                        <input type="hidden" name="id_curso" value="<?php echo trim($_GET["id_curso"]); ?>" />
                        <p>¿Está Seguro que quiere eliminar el registro?</p><br>
                        <p>
                            <input type="submit" value="Eliminar" class="btn btn-danger">
                            <a href="cursos-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>