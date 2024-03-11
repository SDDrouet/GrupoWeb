<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$dia = "";
$hora_inicio = "";
$hora_fin = "";

// Processing form data when form is submitted
if (isset($_POST["id_horario"]) && !empty($_POST["id_horario"])) {
    // Get hidden input value
    $id_horario = $_POST["id_horario"];

    $dia = trim($_POST["dia"]);
    $hora_inicio = trim($_POST["hora_inicio"]);
    $hora_fin = trim($_POST["hora_fin"]);


    // Prepare an update statement
    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    $vars = parse_columns('horarios', $_POST);
    $stmt = $pdo->prepare("UPDATE horarios SET dia=?,hora_inicio=?,hora_fin=? WHERE id_horario=?");

    if (!$stmt->execute([$dia, $hora_inicio, $hora_fin, $id_horario])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: horarios-read.php?id_horario=$id_horario");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_horario"] = trim($_GET["id_horario"]);
    if (isset($_GET["id_horario"]) && !empty($_GET["id_horario"])) {
        // Get URL parameter
        $id_horario = trim($_GET["id_horario"]);

        // Prepare a select statement
        $sql = "SELECT * FROM horarios WHERE id_horario = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_horario;

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

                    // Retrieve individual field value

                    $dia = htmlspecialchars($row["dia"]);
                    $hora_inicio = htmlspecialchars($row["hora_inicio"]);
                    $hora_fin = htmlspecialchars($row["hora_fin"]);


                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    } else {
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
    <title>Actualizar Registro</title>
</head>

<?php include('header.php'); ?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h2>Actualizar Registro</h2>
                </div>
                <p>Porfavor actualiza los campos y envia el formulario para actualizar los cambios.</p>
                <form id="agregar_horarios" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                        <label>Día</label>
                        <input type="text" name="diaaa" maxlength="45" class="form-control" value="<?php echo $dia; ?>"
                            disabled>
                        <input type="text" name="dia" maxlength="45" class="form-control" value="<?php echo $dia; ?>"
                            hidden>
                        <span class="form-text">
                            <?php echo $dia_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Hora de Inicio</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" value="<?php echo $hora_inicio; ?>">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label>Hora de Finalización</label>
                        <input type="time" id="hora_fin" name="hora_fin" class="form-control" value="<?php echo $hora_fin; ?>">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <input type="hidden" name="id_horario" value="<?php echo $id_horario; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="horarios-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_horarios.js"></script>

<?php include('footer.php'); ?>