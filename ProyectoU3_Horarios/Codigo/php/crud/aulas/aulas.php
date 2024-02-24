<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Aulas</title>
</head>
<body>
    <h1>CRUD Aulas</h1>

    <!-- Formulario para agregar aula -->
    <h2>Agregar Aula</h2>
    <form action="insertar_aula.php" method="post">
        ID Aula: <input type="text" name="id_aula" required><br>
        Capacidad: <input type="number" name="capacidad" required><br>
        Bloque: <input type="text" name="bloque" required><br>
        Observación: <input type="text" name="observacion"><br>
        <input type="submit" value="Agregar Aula">
    </form>

    <hr>

    <!-- Mostrar aulas -->
    <h2>Listado de Aulas</h2>
    <?php include 'mostrar_aulas.php'; ?>

</body>
</html>
