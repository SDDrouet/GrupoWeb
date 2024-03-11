<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre_periodo = "";
$fecha_inicio = "";
$fecha_fin = "";

// Processing form data when form is submitted
if (isset($_POST["id_periodo"]) && !empty($_POST["id_periodo"])) {
    // Get hidden input value
    $id_periodo = $_POST["id_periodo"];

    $nombre_periodo = trim($_POST["nombre_periodo"]);
    $fecha_inicio = trim($_POST["fecha_inicio"]);
    $fecha_fin = trim($_POST["fecha_fin"]);


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

    $vars = parse_columns('periodos', $_POST);
    $stmt = $pdo->prepare("UPDATE periodos SET nombre_periodo=?,fecha_inicio=?,fecha_fin=? WHERE id_periodo=?");

    if (!$stmt->execute([$nombre_periodo, $fecha_inicio, $fecha_fin, $id_periodo])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: periodos-read.php?id_periodo=$id_periodo");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_periodo"] = trim($_GET["id_periodo"]);
    if (isset($_GET["id_periodo"]) && !empty($_GET["id_periodo"])) {
        // Get URL parameter
        $id_periodo = trim($_GET["id_periodo"]);

        // Prepare a select statement
        $sql = "SELECT * FROM periodos WHERE id_periodo = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_periodo;

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

                    $nombre_periodo = htmlspecialchars($row["nombre_periodo"]);
                    $fecha_inicio = htmlspecialchars($row["fecha_inicio"]);
                    $fecha_fin = htmlspecialchars($row["fecha_fin"]);


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
                <form id="agregar_periodo" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                <div class="form-group">
                        <label for="nombre_periodo">Nombre Periodo:</label>
                        <input type="text" class="form-control" id="nombre_periodo" name="nombre_periodo"
                            value="<?php echo $nombre_periodo; ?>" requiered>
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                            value="<?php echo $fecha_inicio; ?>">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Finalización</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"  value="<?php echo $fecha_fin; ?>">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <input type="hidden" name="id_periodo" value="<?php echo $id_periodo; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="periodos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_periodos.js"></script>

<?php include('footer.php'); ?>