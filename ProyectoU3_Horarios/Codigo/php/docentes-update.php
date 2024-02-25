<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_docente = "";
$nombres = "";
$apellidos = "";
$horas_disponibles = "";
$tipo_contrato = "";
$correo = "";
$nivel_educacion = "";
$especializacion = "";
$celular = "";
$cedula = "";
$estado = "";

$id_docente_err = "";
$nombres_err = "";
$apellidos_err = "";
$horas_disponibles_err = "";
$tipo_contrato_err = "";
$correo_err = "";
$nivel_educacion_err = "";
$especializacion_err = "";
$celular_err = "";
$cedula_err = "";
$estado_err = "";


// Processing form data when form is submitted
if(isset($_POST["id_docente"]) && !empty($_POST["id_docente"])){
    // Get hidden input value
    $id_docente = $_POST["id_docente"];

    $id_docente = trim($_POST["id_docente"]);
		$nombres = trim($_POST["nombres"]);
		$apellidos = trim($_POST["apellidos"]);
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
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    $vars = parse_columns('docentes', $_POST);
    $stmt = $pdo->prepare("UPDATE docentes SET id_docente=?,nombres=?,apellidos=?,horas_disponibles=?,tipo_contrato=?,correo=?,nivel_educacion=?,especializacion=?,celular=?,cedula=?,estado=? WHERE id_docente=?");

    if(!$stmt->execute([ $id_docente,$nombres,$apellidos,$horas_disponibles,$tipo_contrato,$correo,$nivel_educacion,$especializacion,$celular,$cedula,$estado,$id_docente  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: docentes-read.php?id_docente=$id_docente");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["id_docente"] = trim($_GET["id_docente"]);
    if(isset($_GET["id_docente"]) && !empty($_GET["id_docente"])){
        // Get URL parameter
        $id_docente =  trim($_GET["id_docente"]);

        // Prepare a select statement
        $sql = "SELECT * FROM docentes WHERE id_docente = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $id_docente;

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

                    // Retrieve individual field value

                    $id_docente = htmlspecialchars($row["id_docente"]);
					$nombres = htmlspecialchars($row["nombres"]);
					$apellidos = htmlspecialchars($row["apellidos"]);
					$horas_disponibles = htmlspecialchars($row["horas_disponibles"]);
					$tipo_contrato = htmlspecialchars($row["tipo_contrato"]);
					$correo = htmlspecialchars($row["correo"]);
					$nivel_educacion = htmlspecialchars($row["nivel_educacion"]);
					$especializacion = htmlspecialchars($row["especializacion"]);
					$celular = htmlspecialchars($row["celular"]);
					$cedula = htmlspecialchars($row["cedula"]);
					$estado = htmlspecialchars($row["estado"]);
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
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
                                <label>ID Docente</label>
                                <input type="text" name="id_docente" maxlength="45"class="form-control" value="<?php echo $id_docente; ?>">
                                <span class="form-text"><?php echo $id_docente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Nombres</label>
                                <input type="text" name="nombres" maxlength="45"class="form-control" value="<?php echo $nombres; ?>">
                                <span class="form-text"><?php echo $nombres_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Apellidos</label>
                                <input type="text" name="apellidos" maxlength="45"class="form-control" value="<?php echo $apellidos; ?>">
                                <span class="form-text"><?php echo $apellidos_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horas disponibles</label>
                                <input type="number" name="horas_disponibles" class="form-control" value="<?php echo $horas_disponibles; ?>">
                                <span class="form-text"><?php echo $horas_disponibles_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Tipo de contrato</label>
                                <input type="text" name="tipo_contrato" maxlength="45"class="form-control" value="<?php echo $tipo_contrato; ?>">
                                <span class="form-text"><?php echo $tipo_contrato_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Correo</label>
                                <input type="text" name="correo" maxlength="100"class="form-control" value="<?php echo $correo; ?>">
                                <span class="form-text"><?php echo $correo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Nivel de educación</label>
                                <input type="text" name="nivel_educacion" maxlength="100"class="form-control" value="<?php echo $nivel_educacion; ?>">
                                <span class="form-text"><?php echo $nivel_educacion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Especialización</label>
                                <input type="text" name="especializacion" maxlength="100"class="form-control" value="<?php echo $especializacion; ?>">
                                <span class="form-text"><?php echo $especializacion_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Celular</label>
                                <input type="text" name="celular" maxlength="20"class="form-control" value="<?php echo $celular; ?>">
                                <span class="form-text"><?php echo $celular_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Cédula</label>
                                <input type="text" name="cedula" maxlength="20"class="form-control" value="<?php echo $cedula; ?>">
                                <span class="form-text"><?php echo $cedula_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Estado</label>
                                <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                                <span class="form-text"><?php echo $estado_err; ?></span>
                            </div>

                        <input type="hidden" name="id_docente" value="<?php echo $id_docente; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="docentes-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
