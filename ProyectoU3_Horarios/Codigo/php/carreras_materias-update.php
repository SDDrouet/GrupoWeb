<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_carrera = "";
$cod_materia = "";

$id_carrera_err = "";
$cod_materia_err = "";


// Processing form data when form is submitted
if(isset($_POST["cod_materia"]) && !empty($_POST["cod_materia"])){
    // Get hidden input value
    $cod_materia = $_POST["cod_materia"];

    $id_carrera = trim($_POST["id_carrera"]);
		$cod_materia = trim($_POST["cod_materia"]);
		

    // Prepare an update statement
    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    $vars = parse_columns('carreras_materias', $_POST);
    $stmt = $pdo->prepare("UPDATE carreras_materias SET id_carrera=?,cod_materia=? WHERE cod_materia=?");

    if(!$stmt->execute([ $id_carrera,$cod_materia,$cod_materia  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: carreras_materias-read.php?cod_materia=$cod_materia");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["cod_materia"] = trim($_GET["cod_materia"]);
    if(isset($_GET["cod_materia"]) && !empty($_GET["cod_materia"])){
        // Get URL parameter
        $cod_materia =  trim($_GET["cod_materia"]);

        // Prepare a select statement
        $sql = "SELECT * FROM carreras_materias WHERE cod_materia = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $cod_materia;

            // Bind variables to the prepared statement as parameters
			if (is_int($param_id)) $__vartype = "i";
			elseif (is_string($param_id)) $__vartype = "s";
			elseif (is_numeric($param_id)) $__vartype = "d";
			else $__vartype = "b"; // blob
			mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $id_carrera = htmlspecialchars($row["id_carrera"]);
					$cod_materia = htmlspecialchars($row["cod_materia"]);
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
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
                                <label>Carrera</label>
                                    <select class="form-control" id="id_carrera" name="id_carrera">
                                    <?php
                                        $sql = "SELECT *,id_carrera FROM carreras";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["id_carrera"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["id_carrera"] == $id_carrera){
                                            echo '<option value="' . "$row[id_carrera]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[id_carrera]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $id_carrera_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Código de Materia</label>
                                    <select class="form-control" id="cod_materia" name="cod_materia">
                                    <?php
                                        $sql = "SELECT *,cod_materia FROM materias";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["cod_materia"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["cod_materia"] == $cod_materia){
                                            echo '<option value="' . "$row[cod_materia]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[cod_materia]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $cod_materia_err; ?></span>
                            </div>

                        <input type="hidden" name="cod_materia" value="<?php echo $cod_materia; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="carreras_materias-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
