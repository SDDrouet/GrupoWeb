<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre_periodo = "";
$fecha_inicio = "";
$fecha_fin = "";

$nombre_periodo_err = "";
$fecha_inicio_err = "";
$fecha_fin_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nombre_periodo = trim($_POST["nombre_periodo"]);
		$fecha_inicio = trim($_POST["fecha_inicio"]);
		$fecha_fin = trim($_POST["fecha_fin"]);

        // Checkboxes de horarios seleccionados
        $horarios_seleccionados = isset($_POST['horarios']) ? $_POST['horarios'] : [];
		

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

        $vars = parse_columns('periodos', $_POST);
        $stmt = $pdo->prepare("INSERT INTO periodos (nombre_periodo,fecha_inicio,fecha_fin) VALUES (?,?,?)");

        if($stmt->execute([ $nombre_periodo,$fecha_inicio,$fecha_fin  ])) {
                $stmt = null;

                // Obtenemos el ID del periodo insertado
                $id_periodo = $pdo->lastInsertId();

                // Insertar horarios seleccionados en la tabla periodos_horarios
                foreach ($horarios_seleccionados as $id_horario) {
                    $stmt = $pdo->prepare("INSERT INTO periodos_horarios (id_periodo, id_horario) VALUES (?, ?)");
                    $stmt->execute([$id_periodo, $id_horario]);
                }

                $sql = "SELECT id_aula FROM aulas";
                $aulas = mysqli_query($link, $sql);
                while($rowAula = mysqli_fetch_array($aulas, MYSQLI_ASSOC)) {
                    $rowAula["id_aula"];

                    foreach ($horarios_seleccionados as $id_horario) {
                        $stmt = $pdo->prepare("INSERT INTO horarios_aulas (id_horario, id_aula, id_periodo) VALUES (?, ?, ?)");
                        $stmt->execute([$id_horario, $rowAula["id_aula"], $id_periodo]);
                    }
                }

                $sql = "SELECT id_docente, horas_disponibles FROM docentes WHERE estado = 1";
                $docentes = mysqli_query($link, $sql);
                while($rowDocente = mysqli_fetch_array($docentes, MYSQLI_ASSOC)) {
                    $rowDocente["id_docente"];

                    $stmt = $pdo->prepare("INSERT INTO periodos_docentes (id_periodo, id_docente, horas_asignadas) VALUES (?, ?, ?)");
                    $stmt->execute([$id_periodo, $rowDocente["id_docente"], $rowDocente["horas_disponibles"]]);

                }

                $stmt = null;
                header("location: periodos-index.php");
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
                                <label>Nombre Periodo</label>
                                <input type="text" name="nombre_periodo" maxlength="45"class="form-control" value="<?php echo $nombre_periodo; ?>">
                                <span class="form-text"><?php echo $nombre_periodo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>">
                                <span class="form-text"><?php echo $fecha_inicio_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha de Finalización</label>
                                <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>">
                                <span class="form-text"><?php echo $fecha_fin_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Selecciona los Horarios Disponibles</label><br>
                                <?php
                                    $sql_horarios = "SELECT id_horario, dia, hora_inicio, hora_fin FROM horarios ORDER BY CASE
                                    WHEN dia = 'lunes' THEN 1
                                    WHEN dia = 'martes' THEN 2
                                    WHEN dia = 'miércoles' THEN 3
                                    WHEN dia = 'jueves' THEN 4
                                    WHEN dia = 'viernes' THEN 5
                                    WHEN dia = 'sábado' THEN 6
                                    WHEN dia = 'domingo' THEN 7
                                    ELSE 8
                                    END, hora_inicio";
                                    $result_horarios = mysqli_query($link, $sql_horarios);
                                // Mostrar checkboxes con los horarios disponibles
                                if ($result_horarios->num_rows > 0) {
                                    while ($row = $result_horarios->fetch_assoc()) {
                                        echo '<input type="checkbox" name="horarios[]" value="' . $row["id_horario"] . '"> ' . $row["dia"] . ' ' . $row["hora_inicio"] . ' - ' . $row["hora_fin"] . '<br>';
                                    }
                                } else {
                                    echo "No hay horarios disponibles.";
                                }
                                ?>
                                <span class="form-text"><?php echo $horarios_err; ?></span>
                            </div>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="periodos-index.php" class="btn btn-secondary">Cancelar</a>
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