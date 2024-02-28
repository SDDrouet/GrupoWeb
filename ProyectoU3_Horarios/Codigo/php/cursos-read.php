<?php
// Check existence of id parameter before processing further
$_GET["id_curso"] = trim($_GET["id_curso"]);
if (isset($_GET["id_curso"]) && !empty($_GET["id_curso"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a select statement
    $sql = "SELECT * FROM cursos WHERE id_curso = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Set parameters
        $param_id = trim($_GET["id_curso"]);

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
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else {
            echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ver Registro</title>
</head>

<?php include('header.php'); ?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="page-header">
                    <h1>Ver Registro</h1>
                </div>
                <div class="form-group">
                    <h4>NRC</h4>
                    <p class="form-control-static">
                        <?php echo htmlspecialchars($row["nrc"]); ?>
                    </p>
                </div>
                <div class="form-group">
                    <h4>Periodo</h4>
                    <p class="form-control-static">
                        <?php echo htmlspecialchars($row["periodos_id_periodo"]); ?>
                    </p>
                </div>
                <div class="form-group">
                    <h4>Código de Materia</h4>
                    <p class="form-control-static">
                        <?php echo htmlspecialchars($row["cod_materia"]); ?>
                    </p>
                </div>
                <div class="form-group">
                    <h4>Horario</h4>
                    <p class="form-control-static">
                        <?php echo htmlspecialchars($row["horarios_id_horario"]); ?>
                    </p>
                </div>
                <div class="form-group">
                    <h4>Aula</h4>
                    <p class="form-control-static">
                        <?php echo htmlspecialchars($row["id_aula"]); ?>
                    </p>
                </div>
                <div class="form-group">
                    <h4>Docente</h4>
                    <p class="form-control-static">
                        <?php echo htmlspecialchars($row["id_docente"]); ?>
                    </p>
                </div>

                <p><a href="cursos-index.php" class="btn btn-primary">Regresar</a></p>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>