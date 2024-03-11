<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$cod_aula = "";
$capacidad = "";
$bloque = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_aula = trim($_POST["cod_aula"]);
    $capacidad = trim($_POST["capacidad"]);
    $bloque = trim($_POST["bloque"]);


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

    $vars = parse_columns('aulas', $_POST);
    $stmt = $pdo->prepare("INSERT INTO aulas (cod_aula,capacidad,bloque) VALUES (?,?,?)");

    if($stmt->execute([ $cod_aula,$capacidad,$bloque  ])) {
        $stmt = null;
        header("location: aulas-index.php");
    } else{
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
                <form id="agregar_aula" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>Código Aula</label>
                        <input type="text" id="cod_aula" name="cod_aula" maxlength="7"class="form-control" value="<?php echo $cod_aula; ?>">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label>Capacidad</label>
                        <input type="number" id="capacidad" name="capacidad" class="form-control" value="25" max="40">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

					<div class="form-group">
                        <label>Bloque</label>
                        <input type="text" id="bloque" name="bloque" maxlength="1" class="form-control" value="<?php echo $bloque; ?>">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="aulas-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_aulas.js"></script>

<?php include('footer.php'); ?>