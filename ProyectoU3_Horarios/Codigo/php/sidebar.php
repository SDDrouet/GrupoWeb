<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            <div class="sidebar-brand-text-light">
            <?php
                session_start();
                if (isset($_SESSION['user_name'])) {
                    $user_log = $_SESSION['user_name'];
                    $priv = $_SESSION['privilegios'];
                    $privilegios = explode(" ", $priv);
                    $func = $_SESSION['funciones'];
                    $funciones = explode(' ', $func);
                    echo "$user_log";
                } else {
                    header('location: ../index.html');
                }
            ?>
            </div>
        </div>
    </a>

    <hr class="sidebar-divider my-4">

    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="bx bx-sm bx-home-alt"></i>
            <span>Inicio</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Usuarios y Perfiles
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios"
            aria-expanded="true" aria-controls="collapseUsuarios">
            <i class='bx bx-sm bxs-user-detail'></i>
            <span>Gestion de usuarios</span>
        </a>
        <div id="collapseUsuarios" class="collapse" aria-labelledby="headingUsuarios" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="perfiles-index.php">Perfiles de usuarios</a>
                <a class="collapse-item" href="usuarios-index.php">Usuarios</a>
                <a class="collapse-item" href="docentes-index.php">Docentes</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interfaz Periodo
    </div>

    <li class="nav-item">
        <a class="nav-link" href="periodos-index.php">
            <i class='bx bx-sm bx-detail'></i>
            <span>Gestionar Periodos</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interfaz Carreras
    </div>

    <li class="nav-item">
        <a class="nav-link" href="carreras-index.php">
            <i class='bx bx-sm bx-buildings'></i>
            <span>Gestionar Carreras</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interfaz General
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGeneral"
            aria-expanded="true" aria-controls="collapseGeneral">
            <i class='bx bx-sm bx-server'></i>
            <span>Gestion general</span>
        </a>
        <div id="collapseGeneral" class="collapse" aria-labelledby="headingGeneral" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="materias-index.php">Materias</a>
                <a class="collapse-item" href="aulas-index.php">Aulas</a>
                <a class="collapse-item" href="cursos-index.php">NRC's</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interfaz Horario
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHorario"
            aria-expanded="true" aria-controls="collapseHorario">
            <i class="bx bx-sm bxs-report"></i>
            <span>Gestión de horarios</span>
        </a>
        <div id="collapseHorario" class="collapse" aria-labelledby="headingHorario"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="horarios-index.php">Franjas Horarias</a>
                <a class="collapse-item" href="horarios_aulas_cursos-index.php">Horarios de cursos</a>
                <a class="collapse-item" href="horariosDragDrop.php">Gestor de Horarios UI</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interfaz de Reportes
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportes"
            aria-expanded="true" aria-controls="collapseReportes">
            <i class='bx bx-sm bxs-user-detail'></i>
            <span>Reportes</span>
        </a>
        <div id="collapseReportes" class="collapse" aria-labelledby="headingReportes" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="horariosDocente.php">Horarios de Docentes</a>
            <a class="collapse-item" href="novedades-index.php">Novedades de Aulas</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>