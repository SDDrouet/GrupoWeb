<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$tipo_perfil = "";
$privilegios = "";

// Processing form data when form is submitted
if (isset($_POST["id_perfil"]) && !empty($_POST["id_perfil"])) {
    // Get hidden input value
    $id_perfil = $_POST["id_perfil"];

    $tipo_perfil = trim($_POST["tipo_perfil"]);
    $privilegios_seleccionados = $_POST['privilegios'];
    $privilegios = implode(" ", $privilegios_seleccionados);
    $funciones_seleccionadas = $_POST['funciones'];
    $funciones = implode(" ", $funciones_seleccionadas);


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

    $vars = parse_columns('perfiles', $_POST);
    $stmt = $pdo->prepare("UPDATE perfiles SET tipo_perfil=?,privilegios=?,funciones=? WHERE id_perfil=?");

    if (!$stmt->execute([$tipo_perfil, $privilegios, $funciones, $id_perfil])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: perfiles-read.php?id_perfil=$id_perfil");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_perfil"] = trim($_GET["id_perfil"]);
    if (isset($_GET["id_perfil"]) && !empty($_GET["id_perfil"])) {
        // Get URL parameter
        $id_perfil = trim($_GET["id_perfil"]);

        // Prepare a select statement
        $sql = "SELECT * FROM perfiles WHERE id_perfil = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id_perfil;

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

                    $tipo_perfil = htmlspecialchars($row["tipo_perfil"]);
                    $privilegios = htmlspecialchars($row["privilegios"]);

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
                    <h2>Actualizar Registro</h2>
                </div>
                <p>Porfavor actualiza los campos y envia el formulario para actualizar los cambios.</p>
                <form id="agregar_perfiles" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>"
                    method="post">

                    <div class="form-group">
                        <label>Tipo de perfil</label>
                        <input type="text" class="form-control" id="tipo_perfil" name="tipo_perfil"
                            value="<?php echo $tipo_perfil; ?>" required pattern="[A-Za-z\s]+">
                        <div class="invalid-feedback">Ingresa un perfil válido.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label>Privilegios:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="seleccionar" name="privilegios[]"
                                value="seleccionar">
                            <label class="form-check-label" for="seleccionar">Seleccionar</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="crear" name="privilegios[]"
                                value="crear">
                            <label class="form-check-label" for="crear">Crear</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="modificar" name="privilegios[]"
                                value="modificar">
                            <label class="form-check-label" for="modificar">Modificar</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="eliminar" name="privilegios[]"
                                value="eliminar">
                            <label class="form-check-label" for="eliminar">Eliminar</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Funciones del Menú Disponibles</label><br>
                        <div class="cajaOverLoad">
                            <input type="checkbox" id="perfiles" name="funciones[]" value="perfiles">
                            <label class="form-check-label" for="perfiles">Perfiles</label><br>

                            <input type="checkbox" id="usuarios" name="funciones[]" value="usuarios">
                            <label class="form-check-label" for="usuarios">Usuarios</label><br>

                            <input type="checkbox" id="docentes" name="funciones[]" value="docentes">
                            <label class="form-check-label" for="docentes">Docentes</label><br>

                            <input type="checkbox" id="periodos" name="funciones[]" value="periodos">
                            <label class="form-check-label" for="periodos">Periodos</label><br>

                            <input type="checkbox" id="carreras" name="funciones[]" value="carreras">
                            <label class="form-check-label" for="carreras">Carreras</label><br>

                            <input type="checkbox" id="materias" name="funciones[]" value="materias">
                            <label class="form-check-label" for="materias">Materias</label><br>

                            <input type="checkbox" id="aulas" name="funciones[]" value="aulas">
                            <label class="form-check-label" for="aulas">Aulas</label><br>

                            <input type="checkbox" id="nrc" name="funciones[]" value="nrc">
                            <label class="form-check-label" for="nrc">NRC</label><br>

                            <input type="checkbox" id="franja_horaria" name="funciones[]" value="franja_horaria">
                            <label class="form-check-label" for="franja_horaria">Franja Horaria</label><br>

                            <input type="checkbox" id="gestor_horarios" name="funciones[]" value="gestor_horarios">
                            <label class="form-check-label" for="gestor_horarios">Gestor Horarios</label><br>

                            <input type="checkbox" id="horarios_docentes" name="funciones[]" value="horarios_docentes">
                            <label class="form-check-label" for="horarios_docentes">Horarios de Docentes</label><br>

                            <input type="checkbox" id="novedades" name="funciones[]" value="novedades">
                            <label class="form-check-label" for="novedades">Novedades</label>
                        </div>
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>
                    <script>alert("El perfil se actualizará una vez cerrada la sesión")</script>
                    <input type="hidden" name="id_perfil" value="<?php echo $id_perfil; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="perfiles-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_perfiles.js"></script>

<?php include('footer.php'); ?>