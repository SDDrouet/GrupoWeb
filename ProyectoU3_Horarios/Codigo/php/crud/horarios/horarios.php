<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Horarios</title>
</head>
<body>
    <h1>CRUD Horarios</h1>

    <!-- Formulario para agregar horario -->
    <h2>Agregar Horario</h2>
    <form action="insertar_horario.php" method="post">
        Día: <input type="text" name="dia" required><br>
        Hora Inicio: <input type="time" name="hora_inicio" required><br>
        Hora Fin: <input type="time" name="hora_fin" required><br>
        <input type="submit" value="Agregar Horario">
    </form>

    <hr>

    <!-- Mostrar horarios -->
    <h2>Listado de Horarios</h2>
    <?php include 'mostrar_horarios.php'; ?>

</body>
</html>
