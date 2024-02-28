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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        exit('Something weird happened'); //something a user can understand
    }

    $vars = parse_columns('docentes', $_POST);
    $stmt = $pdo->prepare("INSERT INTO docentes (id_docente,nombres,apellidos,horas_disponibles,tipo_contrato,correo,nivel_educacion,especializacion,celular,cedula,estado) VALUES (?,?,?,?,?,?,?,?,?,?,?)");

    if ($stmt->execute([$id_docente, $nombres, $apellidos, $horas_disponibles, $tipo_contrato, $correo, $nivel_educacion, $especializacion, $celular, $cedula, $estado])) {
        $stmt = null;
        header("location: docentes-index.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crear registro</title>
</head>

<?php include('header.php'); ?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h2>Crear Registro</h2>
                </div>
                <p>Porfavor completa este formulario para ingresarlo a la base de datos.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>ID Docente</label>
                        <input type="text" name="id_docente" maxlength="45" class="form-control"
                            value="<?php echo $id_docente; ?>">
                        <span class="form-text">
                            <?php echo $id_docente_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Nombres</label>
                        <input type="text" name="nombres" maxlength="45" class="form-control"
                            value="<?php echo $nombres; ?>">
                        <span class="form-text">
                            <?php echo $nombres_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" maxlength="45" class="form-control"
                            value="<?php echo $apellidos; ?>">
                        <span class="form-text">
                            <?php echo $apellidos_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Horas disponibles</label>
                        <input type="number" name="horas_disponibles" class="form-control"
                            value="<?php echo $horas_disponibles; ?>">
                        <span class="form-text">
                            <?php echo $horas_disponibles_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Tipo de contrato</label>
                        <input type="text" name="tipo_contrato" maxlength="45" class="form-control"
                            value="<?php echo $tipo_contrato; ?>">
                        <span class="form-text">
                            <?php echo $tipo_contrato_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Correo</label>
                        <input type="text" name="correo" maxlength="100" class="form-control"
                            value="<?php echo $correo; ?>">
                        <span class="form-text">
                            <?php echo $correo_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Nivel de educación</label>
                        <input type="text" name="nivel_educacion" maxlength="100" class="form-control"
                            value="<?php echo $nivel_educacion; ?>">
                        <span class="form-text">
                            <?php echo $nivel_educacion_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Especialización</label>
                        <input type="text" name="especializacion" maxlength="100" class="form-control"
                            value="<?php echo $especializacion; ?>">
                        <span class="form-text">
                            <?php echo $especializacion_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Celular</label>
                        <input type="text" name="celular" maxlength="20" class="form-control"
                            value="<?php echo $celular; ?>">
                        <span class="form-text">
                            <?php echo $celular_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Cédula</label>
                        <input type="text" name="cedula" maxlength="20" class="form-control"
                            value="<?php echo $cedula; ?>">
                        <span class="form-text">
                            <?php echo $cedula_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                        <span class="form-text">
                            <?php echo $estado_err; ?>
                        </span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="docentes-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>