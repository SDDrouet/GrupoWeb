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
                    <h2 class="float-left">Usuarios Detalles</h2>
                    <a href="usuarios-create.php" class="btn btn-success float-right">Nuevo registro</a>
                    <a href="usuarios-index.php" class="btn btn-info float-right mr-2">Refrescar</a>
                    <a href="index.php" class="btn btn-secondary float-right mr-2">Atrás</a>
                </div>

                <div class="form-row">
                    <form action="usuarios-index.php" method="get">
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

                $total_pages_sql = "SELECT COUNT(*) FROM usuarios";
                $result = mysqli_query($link, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                //Column sorting on column name
                $orderBy = array('id_usuario', 'nombre', 'apellido', 'usuario', 'clave', 'id_perfil');
                $order = 'id_perfil';
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
                $sql = "SELECT * FROM usuarios ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                $count_pages = "SELECT * FROM usuarios";

                if (!empty($_GET['search'])) {
                    $search = ($_GET['search']);
                    $sql = "SELECT * FROM usuarios
                            WHERE CONCAT_WS (id_usuario,nombre,apellido,usuario,clave,id_perfil)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM usuarios
                            WHERE CONCAT_WS (id_usuario,nombre,apellido,usuario,clave,id_perfil)
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

                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th><a href=?search=$search&sort=&order=id_usuario&sort=$sort>ID Usuario</th>";
                        echo "<th><a href=?search=$search&sort=&order=nombre&sort=$sort>Nombre</th>";
                        echo "<th><a href=?search=$search&sort=&order=apellido&sort=$sort>Apellido</th>";
                        echo "<th><a href=?search=$search&sort=&order=usuario&sort=$sort>Nombre de Usuario</th>";
                        echo "<th><a href=?search=$search&sort=&order=clave&sort=$sort>Clave</th>";
                        echo "<th><a href=?search=$search&sort=&order=id_perfil&sort=$sort>Perfil</th>";

                        echo "<th>Acción</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id_usuario']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['clave']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['id_perfil']) . "</td>";
                            echo "<td>";
                            echo "<a href='usuarios-read.php?id_perfil=" . $row['id_perfil'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                            echo "<a href='usuarios-update.php?id_perfil=" . $row['id_perfil'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                            echo "<a href='usuarios-delete.php?id_perfil=" . $row['id_perfil'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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