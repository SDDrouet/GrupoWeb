<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_periodo = "";
$id_docente = "";
$horas_asignadas = "";

$id_periodo_err = "";
$id_docente_err = "";
$horas_asignadas_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_periodo = trim($_POST["id_periodo"]);
    $id_docente = trim($_POST["id_docente"]);
    $horas_asignadas = trim($_POST["horas_asignadas"]);


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

    $vars = parse_columns('periodos_docentes', $_POST);
    $stmt = $pdo->prepare("INSERT INTO periodos_docentes (id_periodo,id_docente,horas_asignadas) VALUES (?,?,?)");

    if ($stmt->execute([$id_periodo, $id_docente, $horas_asignadas])) {
        $stmt = null;
        header("location: periodos_docentes-index.php");
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
                        <label>Docente</label>
                        <select class="form-control" id="id_docente" name="id_docente">
                            <?php
                            $sql = "SELECT *,id_docente FROM docentes";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_docente"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_docente"] == $id_docente) {
                                    echo '<option value="' . "$row[id_docente]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_docente]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $id_docente_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Horas asignadas</label>
                        <input type="number" name="horas_asignadas" class="form-control"
                            value="<?php echo $horas_asignadas; ?>">
                        <span class="form-text">
                            <?php echo $horas_asignadas_err; ?>
                        </span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="periodos_docentes-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>