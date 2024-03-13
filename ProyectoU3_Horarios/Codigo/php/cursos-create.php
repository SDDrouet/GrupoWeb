<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$periodos_id_periodo = "";
$id_materia = "";
$id_docente = "";
$nrc = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodos_id_periodo = trim($_POST["periodos_id_periodo"]);
    $id_materia = trim($_POST["id_materia"]);
    $id_docente_Periodo = trim($_POST["id_docente"]);
    $nrc = trim($_POST["nrc"]);
    $row_id_docente_Periodo = explode(',', $id_docente_Periodo);
    $id_docente = $row_id_docente_Periodo[1];
    $id_periodo_docente = $row_id_docente_Periodo[0];

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

    // Verificar si el nrc ya existe
    $stmt_verificar_nrc = $pdo->prepare("SELECT nrc FROM cursos WHERE nrc = ?");
    $stmt_verificar_nrc->execute([$nrc]);
    $existe_nrc = $stmt_verificar_nrc->fetchColumn();

    if ($existe_nrc) {
        // El nrc ya existe, muestra una alerta en JavaScript
        echo "<script>alert('El NRC ya está registrado.');</script>";
    } else {
        // El nrc no existe, procede con la inserción
        $stmt = $pdo->prepare("INSERT INTO cursos (nrc, periodos_id_periodo, id_docente, id_materia) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$nrc, $periodos_id_periodo, $id_docente, $id_materia])) {
            $stmt = null;
            header("location: cursos-index.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crear registro</title>
    <style>
        .cajaOverLoad {
            overflow: auto;
            max-height: 200px;
            width: 50%;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
            padding-left: 20px;
        }
    </style>
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
                <form id="agregar_curso" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>NRC</label>
                        <input type="number" min="1" id="nrc" name="nrc" class="form-control"
                            value="<?php echo $nrc; ?>">
                            <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

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
                        <label>Código de Materia</label>
                        <select class="form-control" id="id_materia" name="id_materia">
                            <?php
                            $sql = "SELECT *,id_materia FROM materias";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["horas_semana"]);
                                unset($duprow["id_materia"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_materia"] == $id_materia) {
                                    echo '<option value="' . "$row[id_materia]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_materia]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Docente</label>
                        <select class="form-control" id="id_docente" name="id_docente">
                            <option value="0,0">No asignado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Carreras Asignadas al NRC</label><br>
                        <div class="cajaOverLoad">
                            <?php
                            $sql_horarios = "SELECT id_carrera, nombre_carrera FROM carreras";
                            $result_horarios = mysqli_query($link, $sql_horarios);
                            // Mostrar checkboxes con los horarios disponibles
                            if ($result_horarios->num_rows > 0) {
                                while ($row = $result_horarios->fetch_assoc()) {
                                    echo '<input type="checkbox" name="horarios[]" value="' . $row["id_carrera"] . '"> ' . $row["nombre_carrera"] . '<br>';
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
                    <a href="cursos-index.php" class="btn btn-secondary">Cancelar</a>
                </form>

            </div>
        </div>
    </div>
</section>

<script>
    function actualizarSelectores() {
        actualizarSelect("periodos_id_periodo", "id_docente", "getOptionsDocentes.php");
        var selector = document.getElementById("id_docente");
        var opcion = document.createElement("option");
        opcion.text = "No asignado";
        opcion.value = "0,0";
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

<script src="../js/formulario_cursos.js"></script>

<?php include('footer.php'); ?>