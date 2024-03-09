<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Horarios</title>
    <style type="text/css">
        .page-header h2 {
            margin-top: 0;
        }

        table tr td:last-child a {
            margin-right: 5px;
        }

        body {
            font-size: 14px;
        }
    </style>
</head>

<?php include('header.php'); ?>
<section class="pt-5">
    <div class="container-fluid">
        <h1>Detalles de Docentes</h1>
        <div class="d-flex justify-content-end align-items-center mb-5">
            <a href="docentes-create.php" class="btn btn-success mr-3"><i class='bx bx-sm bx-plus'></i> Nuevo
                registro</a>
            <a href="docentes-index.php" class="btn btn-info mr-3">Actualizar</a>
            <a href="index.php" class="btn btn-secondary"><i class='bx bx-sm bx-arrow-back'></i> Atrás</a>
        </div>

        <div class="form-row">
            <form action="docentes-index.php" method="get">
                <div class="d-flex">
                    <input type="text" class="form-control mr-2" placeholder="Buscar en la tabla"
                        aria-label="Buscar en la tabla" name="search">
                    <button type="submit" class="btn btn-primary"><i class='bx bx-search-alt-2'></i></button>
                </div>
            </form>
        </div>

        <br>

        <?php
        // Include config file
        require_once "config.php";
        require_once "helpers.php";

        //Get current URL and parameters for correct pagination
        $domain = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $parameters = $_GET ? $_SERVER['QUERY_STRING'] : "";
        $protocol = ($_SERVER['HTTPS'] == "on" ? "https" : "http");
        $currenturl = $protocol . '://' . $domain . $script . '?' . $parameters;

        //Pagination
        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }

        //$no_of_records_per_page is set on the index page. Default is 10.
        $offset = ($pageno - 1) * $no_of_records_per_page;

        $total_pages_sql = "SELECT COUNT(*) FROM docentes";
        $result = mysqli_query($link, $total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        //Column sorting on column name
        $orderBy = array('id_docente', 'nombres', 'apellidos', 'horas_disponibles', 'tipo_contrato', 'correo', 'nivel_educacion', 'especializacion', 'celular', 'cedula', 'estado');
        $order = 'id_docente';
        if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
            $order = $_GET['order'];
        }

        //Column sort order
        $sortBy = array('asc', 'desc');
        $sort = 'desc';
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
            if ($_GET['sort'] == 'asc') {
                $sort = 'desc';
            } else {
                $sort = 'asc';
            }
        }

        // Attempt select query execution
        $sql = "SELECT * FROM docentes ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
        $count_pages = "SELECT * FROM docentes";

        if (!empty($_GET['search'])) {
            $search = ($_GET['search']);
            $sql = "SELECT * FROM docentes
                            WHERE CONCAT_WS (id_docente,nombres,apellidos,horas_disponibles,tipo_contrato,correo,nivel_educacion,especializacion,celular,cedula,estado)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
            $count_pages = "SELECT * FROM docentes
                            WHERE CONCAT_WS (id_docente,nombres,apellidos,horas_disponibles,tipo_contrato,correo,nivel_educacion,especializacion,celular,cedula,estado)
                            LIKE '%$search%'
                            ORDER BY $order $sort";
        } else {
            $search = "";
        }

        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                if ($result_count = mysqli_query($link, $count_pages)) {
                    $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
                }
                $number_of_results = mysqli_num_rows($result_count);
                echo " " . $number_of_results . " Resultado - Página " . $pageno . " de " . $total_pages;

                echo "<div class='card shadow mb-4 p-1'>";
                echo "<div class='card-body'>";
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th><a href=?search=$search&sort=&order=id_docente&sort=$sort>ID Docente</th>";
                echo "<th><a href=?search=$search&sort=&order=nombres&sort=$sort>Nombres</th>";
                echo "<th><a href=?search=$search&sort=&order=apellidos&sort=$sort>Apellidos</th>";
                echo "<th><a href=?search=$search&sort=&order=horas_disponibles&sort=$sort>Horas disponibles</th>";
                echo "<th><a href=?search=$search&sort=&order=tipo_contrato&sort=$sort>Tipo de contrato</th>";
                echo "<th><a href=?search=$search&sort=&order=correo&sort=$sort>Correo</th>";
                echo "<th><a href=?search=$search&sort=&order=nivel_educacion&sort=$sort>Nivel de educación</th>";
                echo "<th><a href=?search=$search&sort=&order=especializacion&sort=$sort>Especialización</th>";
                echo "<th><a href=?search=$search&sort=&order=celular&sort=$sort>Celular</th>";
                echo "<th><a href=?search=$search&sort=&order=cedula&sort=$sort>Cédula</th>";
                echo "<th><a href=?search=$search&sort=&order=estado&sort=$sort>Estado</th>";

                echo "<th>Acción</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id_docente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['horas_disponibles']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tipo_contrato']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nivel_educacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['especializacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['celular']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cedula']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                    echo "<td>";
                    echo "<a href='docentes-read.php?id_docente=" . $row['id_docente'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                    echo "<a href='docentes-update.php?id_docente=" . $row['id_docente'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                    echo "<a href='docentes-delete.php?id_docente=" . $row['id_docente'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                ?>
                <ul class="pagination" align-right>
                    <?php
                    $new_url = preg_replace('/&?pageno=[^&]*/', '', $currenturl);
                    ?>
                    <li class="page-item"><a class="page-link" href="<?php echo $new_url . '&pageno=1' ?>">Primera</a>
                    </li>
                    <li class="page-item <?php if ($pageno <= 1) {
                        echo 'disabled';
                    } ?>">
                        <a class="page-link" href="<?php if ($pageno <= 1) {
                            echo '#';
                        } else {
                            echo $new_url . "&pageno=" . ($pageno - 1);
                        } ?>"><</a>
                    </li>
                    <li class="page-item <?php if ($pageno >= $total_pages) {
                        echo 'disabled';
                    } ?>">
                        <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                            echo '#';
                        } else {
                            echo $new_url . "&pageno=" . ($pageno + 1);
                        } ?>">></a>
                    </li>
                    <li class="page-item <?php if ($pageno >= $total_pages) {
                        echo 'disabled';
                    } ?>">
                        <a class="page-item"><a class="page-link"
                                href="<?php echo $new_url . '&pageno=' . $total_pages; ?>">Última</a>
                    </li>
                </ul>
                <?php
                // Free result set
                mysqli_free_result($result);
            } else {
                echo "<p class='lead'><em>No records were found.</em></p>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }

        // Close connection
        mysqli_close($link);
        ?>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php include('footer.php'); ?>