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
        <h1>Detalles de Aulas</h1>
        <div class="d-flex justify-content-end align-items-center mb-5">
            <a <?php if (!in_array("crear", $privilegios)) {
                echo 'style="display: none;"';
            } ?> href="aulas-create.php"
                class="btn btn-success mr-3"><i class='bx bx-sm bx-plus'></i> Nuevo registro</a>
            <a href="aulas-index.php" class="btn btn-info mr-3">Actualizar</a>
            <a href="index.php" class="btn btn-secondary"><i class='bx bx-sm bx-arrow-back'></i> Atrás</a>
        </div>

        <div class="form-row">
            <form action="aulas-index.php" method="get">
                <div class="d-flex">
                    <input type="text" class="form-control mr-2" placeholder="Buscar en la tabla"
                        aria-label="Buscar en la tabla" name="search" autofocus>
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

        $total_pages_sql = "SELECT COUNT(*) FROM aulas";
        $result = mysqli_query($link, $total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        //Column sorting on column name
        $orderBy = array('id_aula', 'cod_aula', 'capacidad', 'bloque', 'estado');
        $order = 'id_aula';
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
        $sql = "SELECT * FROM aulas ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
        $count_pages = "SELECT * FROM aulas";


        if (!empty($_GET['search'])) {
            $search = ($_GET['search']);
            $sql = "SELECT * FROM aulas
                            WHERE CONCAT_WS (id_aula,capacidad,bloque,observacion,estado)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
            $count_pages = "SELECT * FROM aulas
                            WHERE CONCAT_WS (id_aula,capacidad,bloque,observacion,estado)
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
                echo "<th><a href=?search=$search&sort=&order=id_aula&sort=$sort>ID</th>";
                echo "<th><a href=?search=$search&sort=&order=cod_aula&sort=$sort>Código Aula</th>";
                echo "<th><a href=?search=$search&sort=&order=capacidad&sort=$sort>Capacidad</th>";
                echo "<th><a href=?search=$search&sort=&order=bloque&sort=$sort>Bloque</th>";
                echo "<th><a href=?search=$search&sort=&order=estado&sort=$sort>Estado</th>";

                echo "<th>Acción</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id_aula']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cod_aula']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['capacidad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bloque']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                    echo "<td";
                    if (!in_array("seleccionar", $privilegios)) {
                        echo ' style="display: none;"';
                    }
                    echo ">";

                    if (in_array("seleccionar", $privilegios)) {
                        echo "<a href='aulas-read.php?id_aula=" . $row['id_aula'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                    }

                    if (in_array("modificar", $privilegios)) {
                        echo "<a href='aulas-update.php?id_aula=" . $row['id_aula'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                    }

                    if (in_array("eliminar", $privilegios)) {
                        echo "<a href='aulas-delete.php?id_aula=" . $row['id_aula'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
                    }

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
                        } ?>">
                            << /a>
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