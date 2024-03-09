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
                <form id="agregar_docentes" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="id_docente">ID Docente:</label>
                        <input type="text" class="form-control" id="id_docente" name="id_docente" 
                            value="<?php echo $id_docente; ?>" required pattern="L\d{8}">
                        <small class="form-text text-muted">Ejemplo: L12345678</small>
                        <div class="invalid-feedback">Ingrese un ID válido de acuerdo con el ejemplo.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="nombres">Nombres:</label>
                        <input type="text" class="form-control" id="nombres" name="nombres"
                            value="<?php echo $nombres; ?>" required pattern="[A-Za-z]+(?: [A-Za-z]+)?">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" 
                            value="<?php echo $apellidos; ?>" required pattern="[A-Za-z]+(?: [A-Za-z]+)?">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label  for="horas_disponibles">Horas disponibles</label>
                        <input type="number" class="form-control" id="horas_disponibles" name="horas_disponibles" 
                            value="<?php echo $horas_disponibles; ?>" required maxlength="2" pattern="[0-9]{1,2}">
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
                        <input type="text" class="form-control" id="nivel_educacion" name="nivel_educacion" 
                            value="<?php echo $nivel_educacion; ?>" required pattern="[A-Za-z]+(?: [A-Za-z]+)?">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="especializacion">Especialización</label>
                        <input type="text" class="form-control" id="especializacion" name="especializacion" 
                            value="<?php echo $especializacion; ?>" required pattern="[a-zA-Z ]+">
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

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="docentes-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_docentes.js"></script>

<?php include('footer.php'); ?>