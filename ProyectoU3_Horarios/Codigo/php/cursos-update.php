<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nrc = "";
$periodos_id_periodo = "";
$cod_materia = "";
$horarios_id_horario = "";
$id_aula = "";
$id_docente = "";

$$nrc_err = "";
$periodos_id_periodo_err = "";
$cod_materia_err = "";
$horarios_id_horario_err = "";
$id_aula_err = "";
$id_docente_err = "";


// Processing form data when form is submitted
if (isset($_POST["id_curso"]) && !empty($_POST["id_curso"])) {
    // Get hidden input value
    $id_curso = $_POST["id_curso"];

    $id_periodoA = trim($_POST["id_periodoA"]);
    $cod_materia = trim($_POST["cod_materia"]);
    $aula_horario = trim($_POST["id_horario__aula"]);
    $id_docente_Periodo = trim($_POST["id_docente"]);
    $nrc = trim($_POST["nrc"]);

    $row_aula_horario = explode(',', $aula_horario);
    $id_horario__aulaOriginal = $row_aula_horario[1];
    $aula_horario = $row_aula_horario[0];

    $row_id_docente_Periodo = explode(',', $id_docente_Periodo);
    $id_docente = $row_id_docente_Periodo[1];
    $id_periodo_docente = $row_id_docente_Periodo[0];
    $id_docente_Original = $row_id_docente_Periodo[2];

    // Prepare an update statement
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
        exit('Something weird happened');
    }

    $sql = "SELECT id_horario, id_aula FROM horarios_aulas WHERE id_horario__aula = $aula_horario";

    $horarios_aulas = mysqli_query($link, $sql);
    while ($rowHorarios_aulas = mysqli_fetch_array($horarios_aulas, MYSQLI_ASSOC)) {
        $horarios_id_horario = $rowHorarios_aulas["id_horario"];
        $id_aula = $rowHorarios_aulas["id_aula"];
    }

    $vars = parse_columns('cursos', $_POST);
    $stmt = $pdo->prepare("UPDATE cursos SET horarios_id_horario=?,id_aula=?,id_docente=? WHERE id_curso=?");

    if (!$stmt->execute([$horarios_id_horario, $id_aula, $id_docente, $id_curso])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $sql = "UPDATE horarios_aulas SET disponible = 0
                WHERE id_horario__aula = $aula_horario
                ";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: horarios_aulas" . mysqli_error($conn);
        }

        $sql = "UPDATE horarios_aulas SET disponible = 1
                WHERE id_horario__aula = $id_horario__aulaOriginal";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: id_horario__aulaOriginal" . mysqli_error($conn);
        }

        $sql = "UPDATE `periodos_docentes` 
        SET `horas_asignadas` = `horas_asignadas` - 2
        WHERE `id_periodo` = $id_periodo_docente
        AND `id_docente` = '$id_docente'";

        if (mysqli_query($link, $sql)) {
            echo "<br>$sql<br>";
            echo "Record updated successfully";
        } else {
            echo "<br>$sql<br>";
            echo "Error updating record: periodos_docentes" . mysqli_error($conn);
        }

        $sql = "UPDATE `periodos_docentes` 
        SET `horas_asignadas` = `horas_asignadas` + 2
        WHERE `id_periodo` = $id_periodo_docente
        AND `id_docente` = '$id_docente_Original'";

        if (mysqli_query($link, $sql)) {
            echo "<br>$sql<br>";
            echo "Record updated successfully";
        } else {
            echo "<br>$sql<br>";
            echo "Error updating record: periodos_docentesOriginal" . mysqli_error($conn);
        }


        $stmt = null;
        header("location: cursos-read.php?id_curso=$id_curso");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_curso"] = trim($_GET["id_curso"]);
    if (isset($_GET["id_curso"]) && !empty($_GET["id_curso"])) {
        // Get URL parameter
        $id_curso = trim($_GET["id_curso"]);

        // Prepare a select statement
        $sql = "SELECT * FROM cursos WHERE id_curso = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_curso;

            // Bind variables to the prepared statement as parameters
            if (is_int($param_id))
                $__vartype = "i";
            elseif (is_string($param_id))
                $__vartype = "s";
            elseif (is_numeric($param_id))
                $__vartype = "d";
            else
                $__vartype = "b"; // blob
            mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $nrc = htmlspecialchars($row["nrc"]);
                    $periodos_id_periodo = htmlspecialchars($row["periodos_id_periodo"]);
                    $cod_materia = htmlspecialchars($row["cod_materia"]);
                    $horarios_id_horario = htmlspecialchars($row["horarios_id_horario"]);
                    $id_aula = htmlspecialchars($row["id_aula"]);
                    $id_docente = htmlspecialchars($row["id_docente"]);

                    $sql1 = "SELECT id_horario__aula FROM horarios_aulas
                                WHERE id_aula = '$id_aula'
                                AND id_horario = $horarios_id_horario
                                AND id_periodo = $periodos_id_periodo";

                    $result2 = $link->query($sql1);

                    if ($result2->num_rows > 0) {
                        // output data of each row
                        while ($row = $result2->fetch_assoc()) {
                            $id_horario__aula = $row["id_horario__aula"];
                        }
                    } else {
                        echo "0 results";
                    }

                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Actualizar Registro</title>
</head>

<?php include('header.php'); ?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h2>Actualizar Registro</h2>
                </div>
                <p>Porfavor actualiza los campos y envia el formulario para actualizar los cambios.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                        <label>NRC</label>
                        <input type="number" min="1" id="nrc" name="nrc" class="form-control"
                            value="<?php echo $nrc; ?>" disabled>
                        <span class="form-text">
                            <?php echo $nrc_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Periodo</label>

                        <select class="form-control" id="id_periodoA" name="id_periodoA" disabled>
                            <?php

                            $sql = "SELECT *,id_periodo FROM periodos";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_periodo"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_periodo"] == $periodos_id_periodo) {
                                    echo '<option value="' . "$periodos_id_periodo" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$periodos_id_periodo" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $periodos_id_periodo_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>Código de Materia</label>
                        <select class="form-control" id="cod_materia" name="cod_materia" disabled>
                            <?php
                            $sql = "SELECT *,cod_materia FROM materias";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["cod_materia"]);
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
                        <label>Aula y Horario (Solo se muestran los disponibles): </label>
                        <?php $id_horario__aulaOriginal = $id_horario__aula;
                        echo $id_horario__aulaOriginal; ?>
                        <select class="form-control" id="id_horario__aula" name="id_horario__aula">
                            <?php

                            $sql = "SELECT id_horario__aula, id_aula, dia, hora_inicio, hora_fin FROM horarios_aulas AS ha
                                        INNER JOIN horarios AS a ON ha.id_horario = a.id_horario
                                        WHERE disponible = 1 AND id_periodo = $periodos_id_periodo";

                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_horario__aula"]);
                                $value = implode(" | ", $duprow);
                                echo '<option value="' . "$row[id_horario__aula]" . ',' . $id_horario__aulaOriginal . '">' . "$value" . '</option>';
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $horarios_id_horario_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Docente</label>
                        <select class="form-control" id="id_docente" name="id_docente">
                            <option value="<?php echo $periodos_id_periodo . ',No Asignado,' . $id_docente ?>">No Asignado
                            </option>;
                            <?php
                            $sql = "SELECT id_periodo_docente, pd.id_docente, nombres, apellidos, especializacion  FROM docentes AS d
                                        INNER JOIN periodos_docentes AS pd ON d.id_docente = pd.id_docente
                                        WHERE estado = 1
                                        AND id_periodo = $periodos_id_periodo
                                        AND horas_asignadas > 0";

                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $value = $periodos_id_periodo . ',' . $row['id_docente'] . ',' . $id_docente;
                                unset($row["id_periodo_docente"]);
                                $txt = implode(" | ", $row);
                                if ($row["id_docente"] == $id_docente) {
                                    echo '<option value="' . "$value" . '"selected="selected">' . "$txt" . '</option>';
                                } else {
                                    echo '<option value="' . "$value" . '">' . "$txt" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $id_docente_err; ?>
                        </span>
                    </div>

                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="cursos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer.php'); ?>