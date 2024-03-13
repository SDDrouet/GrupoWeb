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
        <h1>Detalles de usuarios</h1>
        <div class="d-flex justify-content-end align-items-center mb-5">
            <a href="usuarios-create.php" class="btn btn-success mr-3"><i class='bx bx-sm bx-plus'></i> Nuevo registro</a>
            <a href="usuarios-index.php" class="btn btn-info mr-3">Actualizar</a>
            <a href="index.php" class="btn btn-secondary"><i class='bx bx-sm bx-arrow-back'></i> Atrás</a>
        </div>

        <div class="form-row">
            <form action="usuarios-index.php" method="get">
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

        $total_pages_sql = "SELECT COUNT(*) FROM usuarios";
        $result = mysqli_query($link, $total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        //Column sorting on column name
        $orderBy = array('id_usuario', 'cod_usuario', 'nombre', 'apellido', 'usuario', 'clave', 'id_perfil');
        $order = 'id_usuario';
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

        $sql = "SELECT u.id_usuario,u.cod_usuario,u.nombre,u.apellido,
                        u.usuario,u.clave, p.tipo_perfil as id_perfil FROM usuarios u
                        INNER JOIN perfiles p ON u.id_perfil = p.id_perfil
                        ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
        $count_pages = "SELECT u.id_usuario,u.cod_usuario,u.nombre,u.apellido,
                                u.usuario,u.clave, p.tipo_perfil as id_perfil FROM usuarios u
                                INNER JOIN perfiles p ON u.id_perfil = p.id_perfil";

        // Attempt select query execution
        /*
        $sql = "SELECT * FROM usuarios ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
        $count_pages = "SELECT * FROM usuarios";
        */
        if (!empty($_GET['search'])) {
            $search = ($_GET['search']);

            $sql = "SELECT u.id_usuario,u.cod_usuario,u.nombre, u.apellido,
                            u.usuario, u.clave, p.tipo_perfil as id_perfil FROM usuarios u
                            INNER JOIN perfiles p ON u.id_perfil = p.id_perfil
                            WHERE CONCAT_WS(u.id_usuario,u.cod_usuario, u.nombre, u.apellido, u.usuario, u.clave, p.tipo_perfil)
                                LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";

            $count_pages = "SELECT u.id_usuario,u.cod_usuario, u.nombre, u.apellido, u.usuario, u.clave, p.tipo_perfil as id_perfil
                            FROM usuarios u
                            INNER JOIN perfiles p ON u.id_perfil = p.id_perfil
                            WHERE CONCAT_WS(u.id_usuario,u.cod_usuario,u.nombre, u.apellido, u.usuario, u.clave, p.tipo_perfil)
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
                echo "<th><a href=?search=$search&sort=&order=id_usuario&sort=$sort>ID</th>";
                echo "<th><a href=?search=$search&sort=&order=cod_usuario&sort=$sort>ID Usuario</th>";
                echo "<th><a href=?search=$search&sort=&order=nombre&sort=$sort>Nombre</th>";
                echo "<th><a href=?search=$search&sort=&order=apellido&sort=$sort>Apellido</th>";
                echo "<th><a href=?search=$search&sort=&order=usuario&sort=$sort>Nombre de Usuario</th>";
                echo "<th><a href=?search=$search&sort=&order=clave&sort=$sort>Contraseña</th>";
                echo "<th><a href=?search=$search&sort=&order=id_perfil&sort=$sort>Perfil</th>";

                echo "<th>Acción</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cod_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['apellido']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
                    echo "<td>********</td>";
                    echo "<td>" . htmlspecialchars($row['id_perfil']) . "</td>";
                    echo "<td>";
                    echo "<a href='usuarios-read.php?id_usuario=" . $row['id_usuario'] . "' title='Ver Registro' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                    echo "<a href='usuarios-update.php?id_usuario=" . $row['id_usuario'] . "' title='Actualizar Registro' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                    echo "<a href='usuarios-delete.php?id_usuario=" . $row['id_usuario'] . "' title='Eliminar Registro' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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