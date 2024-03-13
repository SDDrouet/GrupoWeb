<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre_periodo = "";
$fecha_inicio = "";
$fecha_fin = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_periodo = trim($_POST["nombre_periodo"]);
    $fecha_inicio = trim($_POST["fecha_inicio"]);
    $fecha_fin = trim($_POST["fecha_fin"]);

    // Checkboxes de horarios seleccionados
    $horarios_seleccionados = isset($_POST['horarios']) ? $_POST['horarios'] : [];


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

    $vars = parse_columns('periodos', $_POST);
    $stmt = $pdo->prepare("INSERT INTO periodos (nombre_periodo,fecha_inicio,fecha_fin) VALUES (?,?,?)");

    if ($stmt->execute([$nombre_periodo, $fecha_inicio, $fecha_fin])) {
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
        while ($rowAula = mysqli_fetch_array($aulas, MYSQLI_ASSOC)) {
            $rowAula["id_aula"];

            foreach ($horarios_seleccionados as $id_horario) {
                $stmt = $pdo->prepare("INSERT INTO horarios_aulas (id_horario, id_aula, id_periodo) VALUES (?, ?, ?)");
                $stmt->execute([$id_horario, $rowAula["id_aula"], $id_periodo]);
            }
        }

        $sql = "SELECT id_docente, horas_disponibles FROM docentes WHERE estado = 1";
        $docentes = mysqli_query($link, $sql);
        while ($rowDocente = mysqli_fetch_array($docentes, MYSQLI_ASSOC)) {
            $rowDocente["id_docente"];

            $stmt = $pdo->prepare("INSERT INTO periodos_docentes (id_periodo, id_docente, horas_asignadas) VALUES (?, ?, ?)");
            $stmt->execute([$id_periodo, $rowDocente["id_docente"], $rowDocente["horas_disponibles"]]);

        }

        $stmt = null;
        header("location: periodos-index.php");
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
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<?php include('header.php'); ?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h2>Crear Registro de Periodo</h2>
                </div>
                <p>Porfavor completa este formulario para ingresarlo a la base de datos.</p>
                <form id="agregar_periodo" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label for="nombre_periodo">Nombre Periodo:</label>
                        <input type="text" class="form-control" id="nombre_periodo" name="nombre_periodo"
                            value="<?php echo $nombre_periodo; ?>" requiered>
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                            value="<?php echo $fecha_inicio; ?>" required>
                        <div class="invalid-feedback">Por favor, ingrese una fecha válida en el formato dd/mm/aaaa</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Finalización</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                            value="<?php echo $fecha_fin; ?>" required>
                        <div class="invalid-feedback">Por favor, ingrese una fecha válida en el formato dd/mm/aaaa</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label>Selecciona los Horarios Disponibles</label><br>
                        <div class="cajaOverLoad">
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
                                    echo '<input type="checkbox" checked name="horarios[]" value="' . $row["id_horario"] . '"> ' . $row["dia"] . ' ' . $row["hora_inicio"] . ' - ' . $row["hora_fin"] . '<br>';
                                }
                            } else {
                                echo "No hay horarios disponibles.";
                            }
                            ?>
                        </div>
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="periodos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_periodos.js"></script>

<?php include('footer.php'); ?>