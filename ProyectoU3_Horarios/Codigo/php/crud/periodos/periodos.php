<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Periodos</title>
</head>
<body>
    <h1>CRUD Periodos</h1>

    <!-- Formulario para agregar periodo -->
    <h2>Agregar Periodo</h2>
    <form action="insertar_periodo.php" method="post">
        Nombre: <input type="text" name="nombre_periodo" required><br>
        Fecha Inicio: <input type="date" name="fecha_inicio" required><br>
        Fecha Fin: <input type="date" name="fecha_fin" required><br>
        <input type="submit" value="Agregar Periodo">
    </form>

    <hr>

    <!-- Mostrar periodos -->
    <h2>Listado de Periodos</h2>
    <?php include 'mostrar_periodos.php'; ?>

</body>
</html>
