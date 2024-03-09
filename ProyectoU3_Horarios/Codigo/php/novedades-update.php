<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$fecha_novedad = "";
$descripcion = "";
$estado = "";
$id_usuario = "";
$id_aula = "";

$fecha_novedad_err = "";
$descripcion_err = "";
$estado_err = "";
$id_usuario_err = "";
$id_aula_err = "";


// Processing form data when form is submitted
if (isset($_POST["id_novedad"]) && !empty($_POST["id_novedad"])) {
    // Get hidden input value
    $id_novedad = $_POST["id_novedad"];

    $fecha_novedad = trim($_POST["fecha_novedad"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = trim($_POST["estado"]);
    $id_usuario = trim($_POST["id_usuario"]);
    $id_aula = trim($_POST["id_aula"]);


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

    $vars = parse_columns('novedades', $_POST);
    $stmt = $pdo->prepare("UPDATE novedades SET fecha_novedad=?,descripcion=?,estado=?,id_usuario=?,id_aula=? WHERE id_novedad=?");

    if (!$stmt->execute([$fecha_novedad, $descripcion, $estado, $id_usuario, $id_aula, $id_novedad])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: novedades-read.php?id_novedad=$id_novedad");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_novedad"] = trim($_GET["id_novedad"]);
    if (isset($_GET["id_novedad"]) && !empty($_GET["id_novedad"])) {
        // Get URL parameter
        $id_novedad = trim($_GET["id_novedad"]);

        // Prepare a select statement
        $sql = "SELECT * FROM novedades WHERE id_novedad = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_novedad;

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

                    $fecha_novedad = htmlspecialchars($row["fecha_novedad"]);
                    $descripcion = htmlspecialchars($row["descripcion"]);
                    $estado = htmlspecialchars($row["estado"]);
                    $id_usuario = htmlspecialchars($row["id_usuario"]);
                    $id_aula = htmlspecialchars($row["id_aula"]);


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
                        <label>Usuario</label>
                        <?php
                            $sql = "SELECT CONCAT(cod_usuario, ' | ', nombre, ' ', apellido) AS cod_usuario,
                                    id_usuario FROM usuarios
                                    WHERE id_usuario = $id_usuario;";
                                    
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $id_usuario = $row["id_usuario"];
                                $cod_usuario = $row["cod_usuario"];
                            }
                        ?>

                        <input hidden type="text" name="id_usuario" maxlength="100" class="form-control"
                            value="<?php echo $id_usuario; ?>">
                        <input readonly type="text" name="cod_usuario" maxlength="100" class="form-control"
                            value="<?php echo $cod_usuario; ?>">

                        
                        <span class="form-text">
                            <?php echo $id_usuario_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Aula</label>
                        <?php
                        $sql = "SELECT cod_aula FROM aulas WHERE id_aula =$id_aula";
                        $result = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $cod_aula = $row["cod_aula"];
                        }
                        ?>
                        <input hidden type="text" name="id_aula" maxlength="100" class="form-control"
                            value="<?php echo $id_aula; ?>">
                        <input readonly type="text" name="cod_aula" maxlength="100" class="form-control"
                            value="<?php echo $cod_aula; ?>">

                        </select>
                        <span class="form-text">
                            <?php echo $id_aula_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea readonly name="descripcion" maxlength="200" class="form-control"
                            placeholder="Escriba detalles de la novedad..."
                            rows="3"><?php echo $descripcion; ?></textarea>

                        <span class="form-text">
                            <?php echo $descripcion_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Fecha</label>
                        <input readonly type="datetime-local" name="fecha_novedad" class="form-control"
                            value="<?php echo date("Y-m-d\TH:i:s", strtotime($fecha_novedad)); ?>">
                        <span class="form-text">
                            <?php echo $fecha_novedad_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="NO RESUELTO" <?php if ($estado == "NO RESUELTO")
                                echo "selected" ?>>NO RESUELTO
                                </option>
                                <option value="RESUELTO" <?php if ($estado == "RESUELTO")
                                echo "selected" ?>>RESUELTO</option>
                            </select>
                            <span class="form-text">
                            <?php echo $estado_err; ?>
                        </span>
                    </div>


                    <input type="hidden" name="id_novedad" value="<?php echo $id_novedad; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="novedades-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>