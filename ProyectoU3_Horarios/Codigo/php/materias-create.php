<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$cod_materia = "";
$nombre_materia = "";
$departamento = "";
$horas_semana = "";

$cod_materia_err = "";
$nombre_materia_err = "";
$departamento_err = "";
$horas_semana_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $cod_materia = trim($_POST["cod_materia"]);
		$nombre_materia = trim($_POST["nombre_materia"]);
		$departamento = trim($_POST["departamento"]);
		$horas_semana = trim($_POST["horas_semana"]);
		

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
          exit('Something weird happened'); //something a user can understand
        }

        $vars = parse_columns('materias', $_POST);
        $stmt = $pdo->prepare("INSERT INTO materias (cod_materia,nombre_materia,departamento,horas_semana) VALUES (?,?,?,?)");

        if($stmt->execute([ $cod_materia,$nombre_materia,$departamento,$horas_semana  ])) {
                $stmt = null;
                header("location: materias-index.php");
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
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
                                <label>Código de Materia</label>
                                <input type="text" name="cod_materia" maxlength="20"class="form-control" value="<?php echo $cod_materia; ?>">
                                <span class="form-text"><?php echo $cod_materia_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Nombre de la Materia</label>
                                <input type="text" name="nombre_materia" maxlength="100"class="form-control" value="<?php echo $nombre_materia; ?>">
                                <span class="form-text"><?php echo $nombre_materia_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Departamento</label>
                                <input type="text" name="departamento" maxlength="45"class="form-control" value="<?php echo $departamento; ?>">
                                <span class="form-text"><?php echo $departamento_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horas semanales</label>
                                <input type="number" name="horas_semana" class="form-control" value="<?php echo $horas_semana; ?>">
                                <span class="form-text"><?php echo $horas_semana_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="materias-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>