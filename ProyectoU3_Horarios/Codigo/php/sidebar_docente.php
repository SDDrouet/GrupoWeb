<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><span id="usuarioNombre"></span></div>
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
                <a class="collapse-item" href="cursos-index.php">Cursos</a>
                <a class="collapse-item" href="novedades-index.php">Novedades de Aulas</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>