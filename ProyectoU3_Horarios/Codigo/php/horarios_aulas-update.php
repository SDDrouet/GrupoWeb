<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_horario = "";
$id_aula = "";
$disponible = "";

$id_horario_err = "";
$id_aula_err = "";
$disponible_err = "";


// Processing form data when form is submitted
if (isset($_POST["id_horario__aula"]) && !empty($_POST["id_horario__aula"])) {
    // Get hidden input value
    $id_horario__aula = $_POST["id_horario__aula"];

    $id_horario = trim($_POST["id_horario"]);
    $id_aula = trim($_POST["id_aula"]);
    $disponible = trim($_POST["disponible"]);

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

    $vars = parse_columns('horarios_aulas', $_POST);
    $stmt = $pdo->prepare("UPDATE horarios_aulas SET id_horario=?,id_aula=?,disponible=? WHERE id_horario__aula=?");

    if (!$stmt->execute([$id_horario, $id_aula, $disponible, $id_horario__aula])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: horarios_aulas-read.php?id_horario__aula=$id_horario__aula");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_horario__aula"] = trim($_GET["id_horario__aula"]);
    if (isset($_GET["id_horario__aula"]) && !empty($_GET["id_horario__aula"])) {
        // Get URL parameter
        $id_horario__aula = trim($_GET["id_horario__aula"]);

        // Prepare a select statement
        $sql = "SELECT * FROM horarios_aulas WHERE id_horario__aula = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_horario__aula;

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

                    $id_horario = htmlspecialchars($row["id_horario"]);
                    $id_aula = htmlspecialchars($row["id_aula"]);
                    $disponible = htmlspecialchars($row["disponible"]);

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
                        <label>Horario</label>
                        <select class="form-control" id="id_horario" name="id_horario">
                            <?php
                            $sql = "SELECT *,id_horario FROM horarios";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_horario"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_horario"] == $id_horario) {
                                    echo '<option value="' . "$row[id_horario]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_horario]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $id_horario_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Aula</label>
                        <select class="form-control" id="id_aula" name="id_aula">
                            <?php
                            $sql = "SELECT *,id_aula FROM aulas";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_aula"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_aula"] == $id_aula) {
                                    echo '<option value="' . "$row[id_aula]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_aula]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $id_aula_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Disponible</label>
                        <input type="number" name="disponible" class="form-control" value="<?php echo $disponible; ?>">
                        <span class="form-text">
                            <?php echo $disponible_err; ?>
                        </span>
                    </div>

                    <input type="hidden" name="id_horario__aula" value="<?php echo $id_horario__aula; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="horarios_aulas-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>