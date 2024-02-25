<?php
// Check existence of id parameter before processing further
$_GET["id_docente"] = trim($_GET["id_docente"]);
if(isset($_GET["id_docente"]) && !empty($_GET["id_docente"])){
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a select statement
    $sql = "SELECT * FROM docentes WHERE id_docente = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Set parameters
        $param_id = trim($_GET["id_docente"]);

        // Bind variables to the prepared statement as parameters
		if (is_int($param_id)) $__vartype = "i";
		elseif (is_string($param_id)) $__vartype = "s";
		elseif (is_numeric($param_id)) $__vartype = "d";
		else $__vartype = "b"; // blob
        mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else{
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="page-header">
                        <h1>Ver Registro</h1>
                    </div>

                     <div class="form-group">
                            <h4>ID Docente</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["id_docente"]); ?></p>
                        </div><div class="form-group">
                            <h4>Nombres</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["nombres"]); ?></p>
                        </div><div class="form-group">
                            <h4>Apellidos</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["apellidos"]); ?></p>
                        </div><div class="form-group">
                            <h4>Horas disponibles</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["horas_disponibles"]); ?></p>
                        </div><div class="form-group">
                            <h4>Tipo de contrato</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["tipo_contrato"]); ?></p>
                        </div><div class="form-group">
                            <h4>Correo</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["correo"]); ?></p>
                        </div><div class="form-group">
                            <h4>Nivel de educación</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["nivel_educacion"]); ?></p>
                        </div><div class="form-group">
                            <h4>Especialización</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["especializacion"]); ?></p>
                        </div><div class="form-group">
                            <h4>Celular</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["celular"]); ?></p>
                        </div><div class="form-group">
                            <h4>Cédula</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["cedula"]); ?></p>
                        </div><div class="form-group">
                            <h4>Estado</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["estado"]); ?></p>
                        </div>

                    <p><a href="docentes-index.php" class="btn btn-primary">Regresar</a></p>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>