<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$dia = "";
$hora_inicio = "";
$hora_fin = "";

$dia_err = "";
$hora_inicio_err = "";
$hora_fin_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = trim($_POST["dia"]);
    $hora_inicio = trim($_POST["hora_inicio"]);
    $hora_fin = trim($_POST["hora_fin"]);

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

    $vars = parse_columns('horarios', $_POST);
    $stmt = $pdo->prepare("INSERT INTO horarios (dia,hora_inicio,hora_fin) VALUES (?,?,?)");

    if ($stmt->execute([$dia, $hora_inicio, $hora_fin])) {
        $stmt = null;
        header("location: horarios-index.php");
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
                        <label>Día</label>
                        <select name="dia" id="dia" class="form-control">
                            <option value="LUNES">LUNES</option>
                            <option value="MARTES">MARTES</option>
                            <option value="MIERCOLES">MIERCOLES</option>
                            <option value="JUEVES">JUEVES</option>
                            <option value="VIERNES">VIERNES</option>
                            <option value="SABADO">SABADO</option>
                            <option value="DOMINGO">DOMINGO</option>
                        </select>
                        <span class="form-text">
                            <?php echo $dia_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Hora de Inicio</label>
                        <input type="time" name="hora_inicio" class="form-control" value="<?php echo $hora_inicio; ?>">
                        <span class="form-text">
                            <?php echo $hora_inicio_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Hora de Finalización</label>
                        <input type="time" name="hora_fin" class="form-control" value="<?php echo $hora_fin; ?>">
                        <span class="form-text">
                            <?php echo $hora_fin_err; ?>
                        </span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="horarios-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>