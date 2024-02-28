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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_materia = trim($_POST["cod_materia"]);
    $nombre_materia = trim($_POST["nombre_materia"]);
    $departamento = trim($_POST["departamento"]);
    $horas_semana = trim($_POST["horas_semana"]);


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

    $vars = parse_columns('materias', $_POST);
    $stmt = $pdo->prepare("INSERT INTO materias (cod_materia,nombre_materia,departamento,horas_semana) VALUES (?,?,?,?)");

    if ($stmt->execute([$cod_materia, $nombre_materia, $departamento, $horas_semana])) {
        $stmt = null;
        header("location: materias-index.php");
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
                        <label>Código de Materia</label>
                        <input type="text" name="cod_materia" maxlength="20" class="form-control"
                            value="<?php echo $cod_materia; ?>">
                        <span class="form-text">
                            <?php echo $cod_materia_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Nombre de la Materia</label>
                        <input type="text" name="nombre_materia" maxlength="100" class="form-control"
                            value="<?php echo $nombre_materia; ?>">
                        <span class="form-text">
                            <?php echo $nombre_materia_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Departamento</label>
                        <select name="departamento" id="departamento" class="form-control">
                            <option value="CIENCIAS EXACTAS">CIENCIAS EXACTAS</option>
                            <option value="CIENCIAS DE LA COMPUTACIÓN">CIENCIAS DE LA COMPUTACIÓN</option>
                            <option value="CIENCIAS DE ENERGÍA Y MECÁNICA">CIENCIAS DE ENERGÍA Y MECÁNICA</option>
                            <option value="CIENCIAS DE LA TIERRA Y LA CONSTRUCCIÓN">CIENCIAS DE LA TIERRA Y LA
                                CONSTRUCCIÓN</option>
                            <option value="CIENCIAS DE LA VIDA Y LA AGRICULTURA">CIENCIAS DE LA VIDA Y LA AGRICULTURA
                            </option>
                            <option value="CIENCIAS ECONÓMICAS, ADMINISTRATIVAS Y DEL COMERCIO">CIENCIAS ECONÓMICAS,
                                ADMINISTRATIVAS Y DEL COMERCIO</option>
                            <option value="CIENCIAS HUMANAS Y SOCIALES">CIENCIAS HUMANAS Y SOCIALES</option>
                            <option value="SEGURIDAD Y DEFENSA">SEGURIDAD Y DEFENSA</option>
                            <option value="ELÉCTRICA, ELECTRÓNICA Y TELECOMUNICACIONES">ELÉCTRICA, ELECTRÓNICA Y
                                TELECOMUNICACIONES</option>
                            <option value="CIENCIAS MÉDICAS">CIENCIAS MÉDICAS</option>

                        </select>
                        <span class="form-text">
                            <?php echo $departamento_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Horas semanales</label>
                        <input type="number" name="horas_semana" class="form-control"
                            value="<?php echo $horas_semana; ?>">
                        <span class="form-text">
                            <?php echo $horas_semana_err; ?>
                        </span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="materias-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>