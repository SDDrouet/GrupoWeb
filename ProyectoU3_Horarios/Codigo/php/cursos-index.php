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
                    <h2 class="float-left">Cursos Detalles</h2>
                    <a href="cursos-create.php" class="btn btn-success float-right">Nuevo registro</a>
                    <a href="cursos-index.php" class="btn btn-info float-right mr-2">Refrescar</a>
                    <a href="index.php" class="btn btn-secondary float-right mr-2">Atrás</a>
                </div>

                <div class="form-row">
                    <form action="cursos-index.php" method="get">
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

                $total_pages_sql = "SELECT COUNT(*) FROM cursos";
                $result = mysqli_query($link, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                //Column sorting on column name
                $orderBy = array('id_curso', 'nrc', 'periodos_id_periodo', 'cod_materia', 'horarios_id_horario', 'id_aula', 'id_docente');
                $order = 'id_curso';
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
                //$sql = "SELECT * FROM cursos ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                $sql = "SELECT c.id_curso, c.nrc, p.nombre_periodo AS periodos_id_periodo,
                CONCAT(m.cod_materia,' | ',m.nombre_materia) AS cod_materia ,
                CONCAT(h.dia,' | ',h.hora_inicio,' | ', h.hora_fin) AS horarios_id_horario,
                a.id_aula, 
                IFNULL(CONCAT(d.nombres,' ', d.apellidos), 'No asignado') AS id_docente
                FROM cursos c
                INNER JOIN periodos p ON c.periodos_id_periodo = p.id_periodo
                INNER JOIN materias m ON c.cod_materia = m.cod_materia
                INNER JOIN horarios h ON c.horarios_id_horario = h.id_horario
                INNER JOIN aulas a ON c.id_aula = a.id_aula
                LEFT JOIN docentes d ON c.id_docente = d.id_docente
                ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page;";

                /*$count_pages = "SELECT c.id_curso, c.nrc, p.nombre_periodo AS periodos_id_periodo,
                CONCAT(m.cod_materia,' | ',m.nombre_materia) AS cod_materia ,
                CONCAT(h.dia,' | ',h.hora_inicio,' | ', h.hora_fin) AS horarios_id_horario,
                a.id_aula, CONCAT(d.nombres,' ', d.apellidos) AS id_docente
                FROM cursos c
                INNER JOIN periodos p ON c.periodos_id_periodo = p.id_periodo
                INNER JOIN materias m ON c.cod_materia = m.cod_materia
                INNER JOIN horarios h ON c.horarios_id_horario = h.id_horario
                INNER JOIN aulas a ON c.id_aula = a.id_aula
                LEFT JOIN docentes d ON c.id_docente = d.id_docente";
                */

                if(!empty($_GET['search'])) {
                $search = ($_GET['search']);
                $sql = "SELECT c.id_curso, c.nrc, p.nombre_periodo AS periodos_id_periodo,
                        CONCAT(m.cod_materia,' | ',m.nombre_materia) AS cod_materia ,
                        CONCAT(h.dia,' | ',h.hora_inicio,' | ', h.hora_fin) AS horarios_id_horario,
                        a.id_aula, 
                        IFNULL(CONCAT(d.nombres,' ', d.apellidos), 'No asignado') AS id_docente
                        FROM cursos c
                        INNER JOIN periodos p ON c.periodos_id_periodo = p.id_periodo
                        INNER JOIN materias m ON c.cod_materia = m.cod_materia
                        INNER JOIN horarios h ON c.horarios_id_horario = h.id_horario
                        INNER JOIN aulas a ON c.id_aula = a.id_aula
                        LEFT JOIN docentes d ON c.id_docente = d.id_docente
                        WHERE 
                            c.id_curso LIKE '%$search%' OR
                            c.nrc LIKE '%$search%' OR
                            p.nombre_periodo LIKE '%$search%' OR
                            m.cod_materia LIKE '%$search%' OR
                            m.nombre_materia LIKE '%$search%' OR
                            CONCAT(h.dia,' | ',h.hora_inicio,' | ', h.hora_fin) LIKE '%$search%' OR
                            a.id_aula LIKE '%$search%' OR
                            d.nombres LIKE '%$search%'
                        ORDER BY $order $sort
                        LIMIT $offset, $no_of_records_per_page";
                /*$count_pages = "SELECT * FROM cursos
                WHERE CONCAT_WS (id_curso,nrc,periodos_id_periodo,cod_materia,horarios_id_horario,id_aula,id_docente)
                LIKE '%$search%'
                ORDER BY $order $sort";*/
                $count_pages = "SELECT c.id_curso, c.nrc, p.nombre_periodo AS periodos_id_periodo,
                CONCAT(m.cod_materia,' | ',m.nombre_materia) AS cod_materia ,
                CONCAT(h.dia,' | ',h.hora_inicio,' | ', h.hora_fin) AS horarios_id_horario,
                a.id_aula, CONCAT(d.nombres,' ', d.apellidos) AS id_docente
                FROM cursos c
                INNER JOIN periodos p ON c.periodos_id_periodo = p.id_periodo
                INNER JOIN materias m ON c.cod_materia = m.cod_materia
                INNER JOIN horarios h ON c.horarios_id_horario = h.id_horario
                INNER JOIN aulas a ON c.id_aula = a.id_aula
                LEFT JOIN docentes d ON c.id_docente = d.id_docente
                WHERE 
                c.id_curso LIKE '%$search%' OR
                c.nrc LIKE '%$search%' OR
                p.nombre_periodo LIKE '%$search%' OR
                m.cod_materia LIKE '%$search%' OR
                m.nombre_materia LIKE '%$search%' OR
                CONCAT(h.dia,' | ',h.hora_inicio,' | ', h.hora_fin) LIKE '%$search%' OR
                a.id_aula LIKE '%$search%' OR
                d.nombres LIKE '%$search%'
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
                        echo "<th><a href=?search=$search&sort=&order=id_curso&sort=$sort>ID Curso</th>";
                        echo "<th><a href=?search=$search&sort=&order=nrc&sort=$sort>NRC</th>";
                        echo "<th><a href=?search=$search&sort=&order=periodos_id_periodo&sort=$sort>Periodo</th>";
                        echo "<th><a href=?search=$search&sort=&order=cod_materia&sort=$sort>Código de Materia</th>";
                        echo "<th><a href=?search=$search&sort=&order=horarios_id_horario&sort=$sort>Horario</th>";
                        echo "<th><a href=?search=$search&sort=&order=id_aula&sort=$sort>Aula</th>";
                        echo "<th><a href=?search=$search&sort=&order=id_docente&sort=$sort>Docente</th>";

                        echo "<th>Acción</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id_curso']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nrc']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['periodos_id_periodo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cod_materia']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['horarios_id_horario']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['id_aula']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['id_docente']) . "</td>";
                            echo "<td>";
                            echo "<a href='cursos-read.php?id_curso=" . $row['id_curso'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                            echo "<a href='cursos-update.php?id_curso=" . $row['id_curso'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                            echo "<a href='cursos-delete.php?id_curso=" . $row['id_curso'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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