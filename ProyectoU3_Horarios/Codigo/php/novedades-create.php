<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$fecha_novedad = "";
$descripcion = "";
$estado = "";
$id_usuario = "";
$id_aula = "";

$fecha_novedad_err = "";
$descripcion_err = "";
$estado_err = "";
$id_usuario_err = "";
$id_aula_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_novedad = trim($_POST["fecha_novedad"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = trim($_POST["estado"]);
    $id_usuario = trim($_POST["id_usuario"]);
    $id_aula = trim($_POST["id_aula"]);


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

    $vars = parse_columns('novedades', $_POST);
    $stmt = $pdo->prepare("INSERT INTO novedades (fecha_novedad,descripcion,estado,id_usuario,id_aula) VALUES (?,?,?,?,?)");

    if ($stmt->execute([$fecha_novedad, $descripcion, $estado, $id_usuario, $id_aula])) {
        $stmt = null;
        header("location: novedades-index.php");
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
                        <label>id_usuario</label>
                        <select class="form-control" id="id_usuario" name="id_usuario">
                            <?php
                            $sql = "SELECT cod_usuario, CONCAT(nombre, ' ', apellido),id_usuario FROM usuarios";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_usuario"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_usuario"] == $id_usuario) {
                                    echo '<option value="' . "$row[id_usuario]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_usuario]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $id_usuario_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>id_aula</label>
                        <select class="form-control" id="id_aula" name="id_aula">
                            <?php
                            $sql = "SELECT cod_aula,id_aula FROM aulas";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_aula"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_aula"] == $id_aula) {
                                    echo '<option value="' . "$row[id_aula]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_aula]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="form-text">
                            <?php echo $id_aula_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" maxlength="200" class="form-control" 
                        placeholder = "Escriba detalles de la novedad..." rows="3"></textarea>
                        <span class="form-text">
                            <?php echo $descripcion_err; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="fecha_novedad">Fecha y Hora</label>
                        <input readonly type="datetime-local" id="fecha_novedad" name="fecha_novedad" class="form-control">
                        <span class="form-text">
                            <?php echo $fecha_novedad_err; ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="NO RESUELTO">NO RESUELTO</option>
                            <option value="RESUELTO">RESUELTO</option>
                        </select>
                        <span class="form-text">
                            <?php echo $estado_err; ?>
                        </span>
                    </div>



                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="novedades-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Obtener el campo de entrada datetime-local
        var campoFecha = document.getElementById("fecha_novedad");

        // Obtener la fecha y hora actual
        var fechaActual = new Date();

        // Formatear la fecha y hora actual en el formato adecuado para datetime-local
        var fechaFormateada = fechaActual.getFullYear() + '-' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ('0' + fechaActual.getDate()).slice(-2) + 'T' + ('0' + fechaActual.getHours()).slice(-2) + ':' + ('0' + fechaActual.getMinutes()).slice(-2);

        // Establecer el valor del campo de entrada datetime-local
        campoFecha.value = fechaFormateada;
    </script>

</section>
<?php include('footer.php'); ?>