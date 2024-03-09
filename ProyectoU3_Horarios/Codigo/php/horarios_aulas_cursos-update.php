<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_horario__aula = "";
$id_curso = "";

$id_horario__aula_err = "";
$id_curso_err = "";


// Processing form data when form is submitted
if (isset($_POST["id_horarios_aulas_cursos"]) && !empty($_POST["id_horarios_aulas_cursos"])) {
    // Get hidden input value
    $id_horarios_aulas_cursos = $_POST["id_horarios_aulas_cursos"];

    $id_horario__aula = trim($_POST["id_horario__aula"]);
    $id_curso = trim($_POST["id_curso"]);
    $id_horario__aula_Original = trim($_POST["id_horario__aula_Original"]);


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

    $vars = parse_columns('horarios_aulas_cursos', $_POST);
    $stmt = $pdo->prepare("UPDATE horarios_aulas_cursos SET id_horario__aula=?,id_curso=? WHERE id_horarios_aulas_cursos=?");

    if (!$stmt->execute([$id_horario__aula, $id_curso, $id_horarios_aulas_cursos])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;

        $sql = "UPDATE horarios_aulas SET disponible = 0
                WHERE id_horario__aula = $id_horario__aula
                ";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: horarios_aulas" . mysqli_error($conn);
        }

        $sql = "UPDATE horarios_aulas SET disponible = 1
                WHERE id_horario__aula = $id_horario__aula_Original";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: id_horario__aula_Original" . mysqli_error($conn);
        }


        header("location: horarios_aulas_cursos-read.php?id_horarios_aulas_cursos=$id_horarios_aulas_cursos");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_horarios_aulas_cursos"] = trim($_GET["id_horarios_aulas_cursos"]);
    if (isset($_GET["id_horarios_aulas_cursos"]) && !empty($_GET["id_horarios_aulas_cursos"])) {
        // Get URL parameter
        $id_horarios_aulas_cursos = trim($_GET["id_horarios_aulas_cursos"]);

        // Prepare a select statement
        $sql = "SELECT * FROM horarios_aulas_cursos WHERE id_horarios_aulas_cursos = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_horarios_aulas_cursos;

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

                    $id_horario__aula = htmlspecialchars($row["id_horario__aula"]);
                    $id_curso = htmlspecialchars($row["id_curso"]);


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

                        <label>Periodo</label>
                        <?php
                        $sql = "SELECT c.periodos_id_periodo, p.nombre_periodo
                                FROM periodos p
                                JOIN cursos c ON c.periodos_id_periodo = p.id_periodo
                                WHERE c.id_curso = $id_curso;";
                        $result = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $periodos_id_periodo = $row["periodos_id_periodo"];
                            $nombre_periodo = $row["nombre_periodo"];
                        }
                        ?>

                        <input hidden type="text" id = "periodos_id_periodo" name="periodos_id_periodo" maxlength="20" class="form-control"
                            value="<?php echo $periodos_id_periodo; ?>">
                        <input readonly type="text" name="nombre_periodo" maxlength="20" class="form-control"
                            value="<?php echo $nombre_periodo; ?>">

                        <span class="form-text">
                            <?php echo $id_curso_err; ?>
                        </span>
                    </div>

                    <div class="form-group">

                        <label>Curso</label>
                        <?php
                        $sql = "SELECT c.id_curso,
                                    CONCAT(m.nombre_materia, ' | ', c.nrc) AS nombre_curso
                                    FROM cursos c
                                    JOIN materias m ON m.id_materia = c.id_materia
                                    ORDER by id_curso DESC";
                        $result = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $duprow = $row;
                            unset($duprow["id_curso"]);
                            $value = $row["nombre_curso"];
                        }
                        ?>
                        <input hidden type="text" name="id_curso" maxlength="20" class="form-control"
                            value="<?php echo $id_curso; ?>">
                        <input readonly type="text" name="id_cursoa" maxlength="20" class="form-control"
                            value="<?php echo $value; ?>">

                        <span class="form-text">
                            <?php echo $id_curso_err; ?>
                        </span>
                    </div>

                    <input hidden type="text" name="id_horario__aula_Original" maxlength="20" class="form-control"
                        value="<?php echo $id_horario__aula; ?>">

                    <div class="form-group">
                        <label for="id_horario__aula">Horario</label>
                        <select class="form-control" id="id_horario__aula" name="id_horario__aula">

                        </select>
                    </div>


                    <input type="hidden" name="id_horarios_aulas_cursos"
                        value="<?php echo $id_horarios_aulas_cursos; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="horarios_aulas_cursos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function actualizarSelectores() {
            actualizarSelect("periodos_id_periodo", "id_horario__aula", "getOptionsAulas_horarios.php");
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
</section>
<?php include('footer.php'); ?>