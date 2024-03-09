<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre_carrera = "";

$nombre_carrera_err = "";


// Processing form data when form is submitted
if (isset($_POST["id_carrera"]) && !empty($_POST["id_carrera"])) {
    // Get hidden input value
    $id_carrera = $_POST["id_carrera"];

    $nombre_carrera = trim($_POST["nombre_carrera"]);


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

    $vars = parse_columns('carreras', $_POST);
    $stmt = $pdo->prepare("UPDATE carreras SET nombre_carrera=? WHERE id_carrera=?");

    if (!$stmt->execute([$nombre_carrera, $id_carrera])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: carreras-read.php?id_carrera=$id_carrera");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_carrera"] = trim($_GET["id_carrera"]);
    if (isset($_GET["id_carrera"]) && !empty($_GET["id_carrera"])) {
        // Get URL parameter
        $id_carrera = trim($_GET["id_carrera"]);

        // Prepare a select statement
        $sql = "SELECT * FROM carreras WHERE id_carrera = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_carrera;

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

                    $nombre_carrera = htmlspecialchars($row["nombre_carrera"]);


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
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                        <label>Nombre Carrera</label>
                        <input type="text" name="nombre_carrera" maxlength="100" class="form-control"
                            value="<?php echo $nombre_carrera; ?>">
                        <span class="form-text">
                            <?php echo $nombre_carrera_err; ?>
                        </span>
                    </div>

                    <input type="hidden" name="id_carrera" value="<?php echo $id_carrera; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="carreras-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>