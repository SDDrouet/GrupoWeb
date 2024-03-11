<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_usuario = "";
$horas_disponibles = "";
$tipo_contrato = "";
$correo = "";
$nivel_educacion = "";
$especializacion = "";
$celular = "";
$cedula = "";
$estado = "";


// Processing form data when form is submitted
if (isset($_POST["id_docente"]) && !empty($_POST["id_docente"])) {
    // Get hidden input value
    $id_docente = $_POST["id_docente"];

		$horas_disponibles = trim($_POST["horas_disponibles"]);
		$tipo_contrato = trim($_POST["tipo_contrato"]);
		$correo = trim($_POST["correo"]);
		$nivel_educacion = trim($_POST["nivel_educacion"]);
		$especializacion = trim($_POST["especializacion"]);
		$celular = trim($_POST["celular"]);
		$cedula = trim($_POST["cedula"]);
		$estado = trim($_POST["estado"]);


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

    $vars = parse_columns('docentes', $_POST);
    $stmt = $pdo->prepare("UPDATE docentes SET horas_disponibles=?,tipo_contrato=?,correo=?,nivel_educacion=?,especializacion=?,celular=?,cedula=?,estado=? WHERE id_docente=?");

    if (!$stmt->execute([$horas_disponibles, $tipo_contrato, $correo, $nivel_educacion, $especializacion, $celular, $cedula, $estado, $id_docente])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: docentes-read.php?id_docente=$id_docente");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_docente"] = trim($_GET["id_docente"]);
    if (isset($_GET["id_docente"]) && !empty($_GET["id_docente"])) {
        // Get URL parameter
        $id_docente = trim($_GET["id_docente"]);

        // Prepare a select statement
        $sql = "SELECT d.id_docente, d.id_usuario, CONCAT(cod_usuario, ' | ' ,nombre, ' ', apellido) AS usuario,
                horas_disponibles, tipo_contrato , correo, nivel_educacion, especializacion,
                celular, cedula, estado
                FROM docentes d
                JOIN usuarios u ON u.id_usuario = d.id_usuario
                WHERE d.id_docente = ?;";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_docente;

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

                    $id_usuario = htmlspecialchars($row["id_usuario"]);
                    $usuario = htmlspecialchars($row["usuario"]);
                    $horas_disponibles = htmlspecialchars($row["horas_disponibles"]);
                    $tipo_contrato = htmlspecialchars($row["tipo_contrato"]);
                    $correo = htmlspecialchars($row["correo"]);
                    $nivel_educacion = htmlspecialchars($row["nivel_educacion"]);
                    $especializacion = htmlspecialchars($row["especializacion"]);
                    $celular = htmlspecialchars($row["celular"]);
                    $cedula = htmlspecialchars($row["cedula"]);
                    $estado = htmlspecialchars($row["estado"]);

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
                <form id="agregar_docentes" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <input hidden type="text" name="id_docente" maxlength="45" class="form-control"
                            value="<?php echo $id_docente; ?>">
                        
                    <div class="form-group">
                        <label>Usuario</label>
                        
                        <input readonly type="text" name="id_usuario" maxlength="45" class="form-control"
                            value="<?php echo $usuario; ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="tipo_contrato">Tipo de Contrato</label>
                        <select name="tipo_contrato" id="tipo_contrato" class="form-control" autofocus>
                            <option value="COMPLETO" <?php if ($tipo_contrato == "COMPLETO") echo "selected"; ?>>COMPLETO</option>
                            <option value="MEDIO" <?php if ($tipo_contrato == "MEDIO") echo "selected"; ?>>MEDIO</option>
                            <option value="OCACIONAL" <?php if ($tipo_contrato == "OCACIONAL") echo "selected"; ?>>OCACIONAL</option>
                        </select>
                        <span class="form-text"><?php echo $tipo_contrato_err; ?></span>
                    </div>

                    <div class="form-group">
                        <label  for="horas_disponibles">Horas disponibles</label>
                        <input type="number" class="form-control" id="horas_disponibles" name="horas_disponibles" 
                            value="<?php echo $horas_disponibles; ?>" required maxlength="2" pattern="[0-9]{1,2}" min = "2" max = "24">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" 
                            value="<?php echo $correo; ?>" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="nivel_educacion">Nivel de educación</label>
                        <select class="form-control" id="nivel_educacion" name="nivel_educacion" required>
                            <option value="SUPERIOR" <?php if ($nivel_educacion == "SUPERIOR") echo "selected"; ?>>SUPERIOR</option>
                            <option value="MAESTRIA" <?php if ($nivel_educacion == "MAESTRIA") echo "selected"; ?>>MAESTRIA</option>
                            <option value="DOCTORADO" <?php if ($nivel_educacion == "DOCTORADO") echo "selected"; ?>>DOCTORADO</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="especializacion">Especialización</label>
                        <input type="text" class="form-control" id="especializacion" name="especializacion" 
                            value="<?php echo $especializacion; ?>" required pattern="[A-Za-z\s]+">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="text" class="form-control" id="celular" name="celular"
                            value="<?php echo $celular; ?>" required maxlength="10" pattern="[0-9]{10}">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="cedula">Cédula</label>
                        <input type="text" class="form-control" id="cedula" name="cedula"
                            value="<?php echo $cedula; ?>" required maxlength="10" pattern="[0-9]{10}">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="1" <?php if ($estado == 1) echo "selected"; ?>>ACTIVO</option>
                            <option value="0" <?php if ($estado == 0) echo "selected"; ?>>INACTIVO</option>
                        </select>
                    </div>

                    <input type="hidden" name="id_docente" value="<?php echo $id_docente; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="docentes-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="../js/formulario_docentes.js"></script>
<?php include('footer.php'); ?>