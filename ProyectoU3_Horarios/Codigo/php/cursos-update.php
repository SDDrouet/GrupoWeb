<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nrc = "";
$periodos_id_periodo = "";
$cod_materia = "";
$id_aula = "";
$id_docente = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get hidden input value
    $id_curso = $_POST["id_curso"];

    $id_periodoA = trim($_POST["id_periodoA"]);
    $cod_materia = trim($_POST["cod_materia"]);
    $id_docente_Periodo = trim($_POST["id_docente"]);
    $nrc = trim($_POST["nrc"]);

    $row_id_docente_Periodo = explode(',', $id_docente_Periodo);
    $id_docente = $row_id_docente_Periodo[1];
    $id_periodo_docente = $row_id_docente_Periodo[0];
    $id_docente_Original = $row_id_docente_Periodo[2];

    // Verify if the new NRC already exists
    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    $stmt = $pdo->prepare("SELECT id_curso FROM cursos WHERE nrc = ? AND id_curso <> ?");
    $stmt->execute([$nrc, $id_curso]);

    if ($stmt->fetchColumn()) {
        // NRC already exists, show alert in JavaScript
        echo "<script>alert('El NRC ya está registrado.');</script>";
    } else {
        // NRC does not exist, proceed with the update
        $stmt = $pdo->prepare("UPDATE cursos SET nrc=?, id_docente=? WHERE id_curso=?");

        if (!$stmt->execute([$nrc, $id_docente, $id_curso])) {
            echo "Something went wrong. Please try again later.";
            header("location: error.php");
        } else {
            $sql = "SELECT COUNT(*) AS numero_cursos
                    FROM horarios_aulas_cursos
                    WHERE id_curso = $id_curso;";
            
            $result = mysqli_query($link, $sql);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $numero_cursos = $row["numero_cursos"];
            }

            $numero_horas = $numero_cursos * 2;

            $sql = "UPDATE `periodos_docentes` 
            SET `horas_asignadas` = `horas_asignadas` - $numero_horas
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
            SET `horas_asignadas` = `horas_asignadas` + $numero_horas
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
                    $id_docente = htmlspecialchars($row["id_docente"]);

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
                <form id="agregar_curso" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                        <label>NRC</label>
                        <input type="number" min="1" id="nrc" name="nrc" class="form-control"
                            value="<?php echo $nrc; ?>">
                            <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
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
                    </div>

                    <div class="form-group">
                        <label>Docente</label>
                        <select class="form-control" id="id_docente" name="id_docente">
                            <option value="<?php echo $periodos_id_periodo . ',No Asignado,' . $id_docente ?>">No Asignado
                            </option>;
                            <?php
                            $sql = "SELECT id_periodo_docente, pd.id_docente, nombre, apellido, especializacion  FROM docentes AS d
                            INNER JOIN periodos_docentes AS pd ON d.id_docente = pd.id_docente
                            INNER JOIN usuarios AS u ON d.id_usuario = u.id_usuario
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
                    </div>

                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="cursos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_cursos.js"></script>

<?php include('footer.php'); ?>