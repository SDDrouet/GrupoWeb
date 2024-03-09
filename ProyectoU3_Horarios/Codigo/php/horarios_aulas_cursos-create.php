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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_horario__aula = trim($_POST["id_horario__aula"]);
    $id_curso = trim($_POST["id_curso"]);


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

    $vars = parse_columns('horarios_aulas_cursos', $_POST);
    $stmt = $pdo->prepare("INSERT INTO horarios_aulas_cursos (id_horario__aula,id_curso) VALUES (?,?)");

    if ($stmt->execute([$id_horario__aula, $id_curso])) {
        $stmt = null;


        $sql = "UPDATE horarios_aulas SET disponible = 0
                WHERE id_horario__aula = $id_horario__aula";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }


        $sql = "SELECT id_periodo_docente
                FROM periodos_docentes pd
                JOIN cursos c ON c.id_docente = pd.id_docente
                WHERE c.id_curso = $id_curso;";
        $result = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $id_periodo_docente = $row["id_periodo_docente"];
        }


        $sql = "UPDATE `periodos_docentes` 
                SET `horas_asignadas` = `horas_asignadas` - 2
                WHERE `id_periodo_docente` = $id_periodo_docente;";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }


        header("location: horarios_aulas_cursos-index.php");
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
                        <select class="form-control" name="periodos_id_periodo" id="periodos_id_periodo"
                            onchange="actualizarSelectores()">
                            <?php
                            $sql = "SELECT *, id_periodo FROM periodos ORDER BY id_periodo DESC";
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
                        <label for="id_curso">Curso</label>
                        <select class="form-control" id="id_curso" name="id_curso">

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_horario__aula">Horario</label>
                        <select class="form-control" id="id_horario__aula" name="id_horario__aula">

                        </select>
                    </div>


                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="horarios_aulas_cursos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function actualizarSelectores() {
            actualizarSelect("periodos_id_periodo", "id_horario__aula", "getOptionsAulas_horarios.php");
            actualizarSelect("periodos_id_periodo", "id_curso", "getOptionsCursos.php");
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