<?php
// Process delete operation after confirmation
if (isset($_POST["id_horario__aula"]) && !empty($_POST["id_horario__aula"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a delete statement
    $sql = "DELETE FROM horarios_aulas WHERE id_horario__aula = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Set parameters
        $param_id = trim($_POST["id_horario__aula"]);

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
            // Records deleted successfully. Redirect to landing page
            header("location: horarios_aulas-index.php");
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
    $_GET["id_horario__aula"] = trim($_GET["id_horario__aula"]);
    if (empty($_GET["id_horario__aula"])) {
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
                        <input type="hidden" name="id_horario__aula"
                            value="<?php echo trim($_GET["id_horario__aula"]); ?>" />
                        <p>¿Está Seguro que quiere eliminar el registro?</p><br>
                        <p>
                            <input type="submit" value="Eliminar" class="btn btn-danger">
                            <a href="horarios_aulas-index.php" class="btn btn-secondary">Cancelar</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>