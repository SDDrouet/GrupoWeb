<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_periodo = "";
$id_horario = "";

$id_periodo_err = "";
$id_horario_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_periodo = trim($_POST["id_periodo"]);
    $id_horario = trim($_POST["id_horario"]);


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

    $vars = parse_columns('periodos_horarios', $_POST);
    $stmt = $pdo->prepare("INSERT INTO periodos_horarios (id_periodo,id_horario) VALUES (?,?)");

    if ($stmt->execute([$id_periodo, $id_horario])) {
        $stmt = null;
        header("location: periodos_horarios-index.php");
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
                        <label>Periodo</label>
                        <select class="form-control" id="id_periodo" name="id_periodo">
                            <?php
                            $sql = "SELECT *,id_periodo FROM periodos";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_periodo"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_periodo"] == $id_periodo) {
                                    echo '<option value="' . "$row[id_periodo]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_periodo]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $id_periodo_err; ?>
                        </span>
                    </div>
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

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="periodos_horarios-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>