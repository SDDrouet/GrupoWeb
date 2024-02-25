<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_periodo = "";
$id_docente = "";
$horas_asignadas = "";

$id_periodo_err = "";
$id_docente_err = "";
$horas_asignadas_err = "";


// Processing form data when form is submitted
if(isset($_POST["id_docente"]) && !empty($_POST["id_docente"])){
    // Get hidden input value
    $id_docente = $_POST["id_docente"];

    $id_periodo = trim($_POST["id_periodo"]);
		$id_docente = trim($_POST["id_docente"]);
		$horas_asignadas = trim($_POST["horas_asignadas"]);
		

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

    $vars = parse_columns('periodos_docentes', $_POST);
    $stmt = $pdo->prepare("UPDATE periodos_docentes SET id_periodo=?,id_docente=?,horas_asignadas=? WHERE id_docente=?");

    if(!$stmt->execute([ $id_periodo,$id_docente,$horas_asignadas,$id_docente  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: periodos_docentes-read.php?id_docente=$id_docente");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["id_docente"] = trim($_GET["id_docente"]);
    if(isset($_GET["id_docente"]) && !empty($_GET["id_docente"])){
        // Get URL parameter
        $id_docente =  trim($_GET["id_docente"]);

        // Prepare a select statement
        $sql = "SELECT * FROM periodos_docentes WHERE id_docente = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $id_docente;

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

                    $id_periodo = htmlspecialchars($row["id_periodo"]);
					$id_docente = htmlspecialchars($row["id_docente"]);
					$horas_asignadas = htmlspecialchars($row["horas_asignadas"]);
					

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
                                <label>Periodo</label>
                                    <select class="form-control" id="id_periodo" name="id_periodo">
                                    <?php
                                        $sql = "SELECT *,id_periodo FROM periodos";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["id_periodo"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["id_periodo"] == $id_periodo){
                                            echo '<option value="' . "$row[id_periodo]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[id_periodo]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $id_periodo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Docente</label>
                                    <select class="form-control" id="id_docente" name="id_docente">
                                    <?php
                                        $sql = "SELECT *,id_docente FROM docentes";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["id_docente"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["id_docente"] == $id_docente){
                                            echo '<option value="' . "$row[id_docente]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[id_docente]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $id_docente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horas asignadas</label>
                                <input type="number" name="horas_asignadas" class="form-control" value="<?php echo $horas_asignadas; ?>">
                                <span class="form-text"><?php echo $horas_asignadas_err; ?></span>
                            </div>

                        <input type="hidden" name="id_docente" value="<?php echo $id_docente; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="periodos_docentes-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
