<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_aula = "";
$capacidad = "";
$bloque = "";
$cod_aula = "";


$id_aula_err = "";
$capacidad_err = "";
$bloque_err = "";
$cod_aula_err = "";


// Processing form data when form is submitted
if (isset($_POST["id_aula"]) && !empty($_POST["id_aula"])) {
    // Get hidden input value
    $id_aula = $_POST["id_aula"];

    $id_aula = trim($_POST["id_aula"]);
    $capacidad = trim($_POST["capacidad"]);
    $bloque = trim($_POST["bloque"]);
    $cod_aula = trim($_POST["cod_aula"]);


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

    $vars = parse_columns('aulas', $_POST);
    $stmt = $pdo->prepare("UPDATE aulas SET id_aula=?,capacidad=?,bloque=?,cod_aula=? WHERE id_aula=?");

    if (!$stmt->execute([$id_aula, $capacidad, $bloque, $cod_aula, $id_aula])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: aulas-read.php?id_aula=$id_aula");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_aula"] = trim($_GET["id_aula"]);
    if (isset($_GET["id_aula"]) && !empty($_GET["id_aula"])) {
        // Get URL parameter
        $id_aula = trim($_GET["id_aula"]);

        // Prepare a select statement
        $sql = "SELECT * FROM aulas WHERE id_aula = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_aula;

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

                    $id_aula = htmlspecialchars($row["id_aula"]);
                    $capacidad = htmlspecialchars($row["capacidad"]);
                    $bloque = htmlspecialchars($row["bloque"]);
                    $cod_aula = htmlspecialchars($row["cod_aula"]);


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

                        <input hidden type="text" name="id_aula" maxlength="30" class="form-control"
                            value="<?php echo $id_aula; ?>">
                        

                    <div class="form-group">
                        <label>Código Aula</label>
                        <input readonly type="text" name="cod_aula" maxlength="30" class="form-control"
                            value="<?php echo $cod_aula; ?>">
                        <span class="form-text">
                            <?php echo $cod_aula_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Capacidad</label>
                        <input type="number" name="capacidad" class="form-control" value="<?php echo $capacidad; ?>">
                        <span class="form-text">
                            <?php echo $capacidad_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Bloque</label>
                        <input readonly type="text" name="bloque" maxlength="10" class="form-control"
                            value="<?php echo $bloque; ?>">
                        <span class="form-text">
                            <?php echo $bloque_err; ?>
                        </span>
                    </div>

                    <input type="hidden" name="id_aula" value="<?php echo $id_aula; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="aulas-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>