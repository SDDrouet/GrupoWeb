<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$periodos_id_periodo = "";
$cod_materia = "";
$aula_horario = "";
$id_docente = "";
$nrc = "";

$periodos_id_periodo_err = "";
$cod_materia_err = "";
$aula_horario = "";
$id_docente_err = "";
$nrc_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodos_id_periodo = trim($_POST["periodos_id_periodo"]);
    $cod_materia = trim($_POST["cod_materia"]);
    $aula_horario = trim($_POST["aula_horario"]);
    $id_docente_Periodo = trim($_POST["id_docente"]);
    $nrc = trim($_POST["nrc"]);
    $row_id_docente_Periodo = explode(',', $id_docente_Periodo);
    $id_docente = $row_id_docente_Periodo[1];
    $id_periodo_docente = $row_id_docente_Periodo[0];

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

    $sql = "SELECT id_horario, id_aula FROM horarios_aulas WHERE id_horario__aula = $aula_horario";
    $horarios_aulas = mysqli_query($link, $sql);
    while ($rowHorarios_aulas = mysqli_fetch_array($horarios_aulas, MYSQLI_ASSOC)) {
        $horarios_id_horario = $rowHorarios_aulas["id_horario"];
        $id_aula = $rowHorarios_aulas["id_aula"];
    }

    $vars = parse_columns('cursos', $_POST);
    $stmt = $pdo->prepare("INSERT INTO cursos (nrc,periodos_id_periodo,cod_materia,horarios_id_horario,id_aula,id_docente) VALUES (?,?,?,?,?,?)");

    if ($stmt->execute([$nrc, $periodos_id_periodo, $cod_materia, $horarios_id_horario, $id_aula, $id_docente])) {
        $stmt = null;

        $sql = "UPDATE horarios_aulas SET disponible = 0
                WHERE id_horario__aula = $aula_horario";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        $sql = "UPDATE `periodos_docentes` 
                SET `horas_asignadas` = `horas_asignadas` - 2
                WHERE `id_periodo` = $periodos_id_periodo 
                AND `id_docente` = '$id_docente'";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        header("location: cursos-index.php");
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
                        <label>NRC</label>
                        <input type="number" min="1" id="nrc" name="nrc" class="form-control"
                            value="<?php echo $nrc; ?>">
                        <span class="form-text">
                            <?php echo $nrc_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Periodo</label>
                        <select class="form-control" name="periodos_id_periodo" id="periodos_id_periodo"
                            onchange="actualizarSelectores()">
                            <?php
                            $sql = "SELECT *,id_periodo FROM periodos";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_periodo"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_periodo"] == $periodos_id_periodo) {
                                    echo '<option value="' . "$row[id_periodo]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_periodo]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Código de Materia</label>
                        <select class="form-control" id="cod_materia" name="cod_materia">
                            <?php
                            $sql = "SELECT *,cod_materia FROM materias";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["horas_semana"]);
                                $value = implode(" | ", $duprow);
                                if ($row["cod_materia"] == $cod_materia) {
                                    echo '<option value="' . "$row[cod_materia]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[cod_materia]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $cod_materia_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Aula y Horario</label>
                        <select class="form-control" id="aula_horario" name="aula_horario">

                        </select>
                        <span class="form-text">
                            <?php echo $aula_horario_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Docente</label>
                        <select class="form-control" id="id_docente" name="id_docente">
                            <option value="">No asignado</option>
                        </select>
                        <span class="form-text">
                            <?php echo $id_docente_err; ?>
                        </span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="cursos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>

            </div>
        </div>
    </div>
</section>

<script>
    function actualizarSelectores() {
        actualizarSelect("periodos_id_periodo", "aula_horario", "getOptionsAulas_horarios.php");
        actualizarSelect("periodos_id_periodo", "id_docente", "getOptionsDocentes.php");
        var selector = document.getElementById("id_docente");
        var opcion = document.createElement("option");
        opcion.text = "No asignado";
        opcion.value = "";
        selector.add(opcion);
    }

    function actualizarSelect(sel1, sel2, arch) {
        var selector1 = document.getElementById(sel1);
        var selector2 = document.getElementById(sel2);

        // Get selected value of selector1
        var valorSelector1 = selector1.value;

        // Clear options in selector2 and selector3
        selector2.innerHTML = '';

        // AJAX request to get options for selector2 and selector3 based on selector1 value
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var options = JSON.parse(xhr.responseText);
                    options.forEach(function (option) {
                        agregarOpcion(selector2, option.texto, option.valor);
                    });
                } else {
                    console.error('Error fetching options:', xhr.status);
                }
            }
        };
        xhr.open('GET', arch + '?selector1=' + valorSelector1, true);
        xhr.send();
    }

    function agregarOpcion(selector, texto, valor) {
        var opcion = document.createElement("option");
        opcion.text = texto;
        opcion.value = valor;
        selector.add(opcion);
    }

    // Call actualizarSelectores on page load to initialize the selectors
    window.onload = function () {
        actualizarSelectores();
    };
</script>

<?php include('footer.php'); ?>