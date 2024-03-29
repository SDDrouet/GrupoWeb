<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_usuario = "";
$nombre = "";
$apellido = "";
$usuario = "";
$clave = "";
$id_perfil = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = trim($_POST["id_usuario"]);
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $usuario = trim($_POST["usuario"]);
    $clave = trim($_POST["clave"]);
    $id_perfil = trim($_POST["id_perfil"]);


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

    $vars = parse_columns('usuarios', $_POST);
    $stmt = $pdo->prepare("INSERT INTO usuarios (id_usuario,nombre,apellido,usuario,clave,id_perfil) VALUES (?,?,?,?,?,?)");

    if ($stmt->execute([$id_usuario, $nombre, $apellido, $usuario, $clave, $id_perfil])) {
        $stmt = null;
        header("location: usuarios-index.php");
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
                    <h2>Crear Registro de Usuario</h2>
                </div>
                <p>Porfavor completa este formulario para ingresarlo a la base de datos.</p>
                <form id="agregar_usuario" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="id_usuario">ID Usuario:</label>
                        <input type="text" class="form-control" id="id_usuario" name="id_usuario"
                            value="<?php echo $id_usuario; ?>" required pattern="L\d{8}">
                        <small class="form-text text-muted">Ejemplo: L12345678</small>
                        <div class="invalid-feedback">Ingrese un ID válido de acuerdo con el ejemplo.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                            value="<?php echo $nombre; ?>" required pattern="[A-Za-z]+(?: [A-Za-z]+)?">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                            value="<?php echo $apellido; ?>" required pattern="[A-Za-z]+(?: [A-Za-z]+)?">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="usuario">Nombre de Usuario:</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" 
                            value="<?php echo $usuario; ?>" required pattern="[a-zA-Z0-9]+">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="clave">Contraseña:</label>
                        <input type="password" class="form-control" id="clave" name="clave"
                            value="<?php echo $clave; ?>" required pattern="^(?=.*[A-Z])(?=.*[a-z]).{8,}$">
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label>Perfil:</label>
                        <select class="form-control" id="id_perfil" name="id_perfil" required>
                            <?php
                            $sql = "SELECT *,id_perfil FROM perfiles";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $duprow = $row;
                                unset($duprow["id_perfil"]);
                                $value = implode(" | ", $duprow);
                                if ($row["id_perfil"] == $id_perfil) {
                                    echo '<option value="' . "$row[id_perfil]" . '"selected="selected">' . "$value" . '</option>';
                                } else {
                                    echo '<option value="' . "$row[id_perfil]" . '">' . "$value" . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Porfavor seleccione un perfil.</div>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <a href="usuarios-index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="../js/formulario_usuarios.js"></script>

<?php include('footer.php'); ?>