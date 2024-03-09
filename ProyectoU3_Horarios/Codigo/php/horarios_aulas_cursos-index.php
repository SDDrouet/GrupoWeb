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
        <div class="row">
            <div class="col-md-12">
                <div class="page-header clearfix">
                    <h2 class="float-left">Horarios Cursos Detalles</h2>
                    <a href="horarios_aulas_cursos-create.php" class="btn btn-success float-right">Nuevo registro</a>
                    <a href="horarios_aulas_cursos-index.php" class="btn btn-info float-right mr-2">Refrescar</a>
                    <a href="index.php" class="btn btn-secondary float-right mr-2">Atrás</a>
                </div>

                <div class="form-row">
                    <form action="horarios_aulas_cursos-index.php" method="get">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Buscar en la tabla" name="search">
                        </div>
                </div>
                </form>
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

                $total_pages_sql = "SELECT COUNT(*) FROM horarios_aulas_cursos";
                $result = mysqli_query($link, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                //Column sorting on column name
                $orderBy = array('id_horarios_aulas_cursos', 'id_horario__aula', 'id_curso');
                $order = 'id_horarios_aulas_cursos';
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
                $sql = "SELECT hac.id_horarios_aulas_cursos,
                CONCAT(m.nombre_materia, ' | ', c.nrc) AS id_curso,
                CONCAT(a.cod_aula ,' | ', h.dia, ' ', h.hora_inicio, ' - ', h.hora_fin) AS id_horario__aula
                FROM horarios_aulas_cursos hac
                JOIN horarios_aulas ha ON ha.id_horario__aula = hac.id_horario__aula
                JOIN horarios h ON h.id_horario = ha.id_horario
                JOIN aulas a ON a.id_aula = ha.id_aula
                JOIN cursos c ON c.id_curso = hac.id_curso
                JOIN materias m ON m.id_materia = c.id_materia ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                $count_pages = "SELECT hac.id_horarios_aulas_cursos,
                        CONCAT(m.nombre_materia, ' | ', c.nrc) AS id_curso,
                        CONCAT(a.cod_aula ,' | ', h.dia, ' ', h.hora_inicio, ' - ', h.hora_fin) AS id_horario__aula
                        FROM horarios_aulas_cursos hac
                        JOIN horarios_aulas ha ON ha.id_horario__aula = hac.id_horario__aula
                        JOIN horarios h ON h.id_horario = ha.id_horario
                        JOIN aulas a ON a.id_aula = ha.id_aula
                        JOIN cursos c ON c.id_curso = hac.id_curso
                        JOIN materias m ON m.id_materia = c.id_materia";


                if (!empty($_GET['search'])) {
                    $search = ($_GET['search']);
                    $sql = "SELECT hac.id_horarios_aulas_cursos,
                            CONCAT(m.nombre_materia, ' | ', c.nrc) AS id_curso,
                            CONCAT(a.cod_aula ,' | ', h.dia, ' ', h.hora_inicio, ' - ', h.hora_fin) AS id_horario__aula
                            FROM horarios_aulas_cursos hac
                            JOIN horarios_aulas ha ON ha.id_horario__aula = hac.id_horario__aula
                            JOIN horarios h ON h.id_horario = ha.id_horario
                            JOIN aulas a ON a.id_aula = ha.id_aula
                            JOIN cursos c ON c.id_curso = hac.id_curso
                            JOIN materias m ON m.id_materia = c.id_materia
                            WHERE hac.id_horarios_aulas_cursos LIKE '%$search%'
                                OR CONCAT_WS (m.nombre_materia, ' | ', c.nrc) LIKE '%$search%'
                                OR CONCAT_WS (a.cod_aula ,' | ', h.dia, ' ', h.hora_inicio, ' - ', h.hora_fin) LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT hac.id_horarios_aulas_cursos,
                            CONCAT(m.nombre_materia, ' | ', c.nrc) AS id_curso,
                            CONCAT(a.cod_aula ,' | ', h.dia, ' ', h.hora_inicio, ' - ', h.hora_fin) AS id_horario__aula
                            FROM horarios_aulas_cursos hac
                            JOIN horarios_aulas ha ON ha.id_horario__aula = hac.id_horario__aula
                            JOIN horarios h ON h.id_horario = ha.id_horario
                            JOIN aulas a ON a.id_aula = ha.id_aula
                            JOIN cursos c ON c.id_curso = hac.id_curso
                            JOIN materias m ON m.id_materia = c.id_materia
                            WHERE hac.id_horarios_aulas_cursos LIKE '%$search%'
                                OR CONCAT_WS (m.nombre_materia, ' | ', c.nrc) LIKE '%$search%'
                                OR CONCAT_WS (a.cod_aula ,' | ', h.dia, ' ', h.hora_inicio, ' - ', h.hora_fin) LIKE '%$search%'
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

                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th><a href=?search=$search&sort=&order=id_horarios_aulas_cursos&sort=$sort>ID</th>";
                        echo "<th><a href=?search=$search&sort=&order=id_curso&sort=$sort>Curso</th>";
                        echo "<th><a href=?search=$search&sort=&order=id_horario__aula&sort=$sort>Horario</th>";
                        

                        echo "<th>Acción</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id_horarios_aulas_cursos']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['id_curso']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['id_horario__aula']) . "</td>";
                            echo "<td>";
                            echo "<a href='horarios_aulas_cursos-read.php?id_horarios_aulas_cursos=" . $row['id_horarios_aulas_cursos'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                            echo "<a href='horarios_aulas_cursos-update.php?id_horarios_aulas_cursos=" . $row['id_horarios_aulas_cursos'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                            echo "<a href='horarios_aulas_cursos-delete.php?id_horarios_aulas_cursos=" . $row['id_horarios_aulas_cursos'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
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
                                <a class="page-link"
                                    href="<?php if ($pageno <= 1) {
                                        echo '#';
                                    } else {
                                        echo $new_url . "&pageno=" . ($pageno - 1);
                                    } ?>">Previa</a>
                            </li>
                            <li class="page-item <?php if ($pageno >= $total_pages) {
                                echo 'disabled';
                            } ?>">
                                <a class="page-link"
                                    href="<?php if ($pageno >= $total_pages) {
                                        echo '#';
                                    } else {
                                        echo $new_url . "&pageno=" . ($pageno + 1);
                                    } ?>">Siguiente</a>
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
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php include('footer.php'); ?>