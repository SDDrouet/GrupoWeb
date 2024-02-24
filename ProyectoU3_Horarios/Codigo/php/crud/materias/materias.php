<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Materias</title>
</head>
<body>
    <h1>CRUD Materias</h1>

    <!-- Formulario para agregar materia -->
    <h2>Agregar Materia</h2>
    <form action="insertar_materia.php" method="post">
        Código Materia: <input type="text" name="cod_materia" required><br>
        Nombre Materia: <input type="text" name="nombre_materia" required><br>
        Departamento: <input type="text" name="departamento" required><br>
        Horas Semana: <input type="number" name="horas_semana" required><br>
        <input type="submit" value="Agregar Materia">
    </form>

    <hr>

    <!-- Mostrar materias -->
    <h2>Listado de Materias</h2>
    <?php include 'mostrar_materias.php'; ?>

</body>
</html>

