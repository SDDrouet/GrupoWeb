<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Docentes</title>
</head>
<body>
    <h1>CRUD Docentes</h1>

    <!-- Formulario para agregar docente -->
    <h2>Agregar Docente</h2>
    <form action="insertar_docente.php" method="post">
        ID Docente: <input type="text" name="id_docente" required><br>
        Nombres: <input type="text" name="nombres" required><br>
        Apellidos: <input type="text" name="apellidos" required><br>
        Horas Disponibles: <input type="number" name="horas_disponibles" required><br>
        Tipo Contrato: <input type="text" name="tipo_contrato" required><br>
        Correo: <input type="email" name="correo" required><br>
        Nivel Educación: <input type="text" name="nivel_educacion" required><br>
        Especialización: <input type="text" name="especializacion" required><br>
        Celular: <input type="text" name="celular" required><br>
        Cedula: <input type="text" name="cedula" required><br>
        <input type="submit" value="Agregar Docente">
    </form>

    <hr>

    <!-- Mostrar docentes -->
    <h2>Listado de Docentes</h2>
    <?php include 'mostrar_docentes.php'; ?>

</body>
</html>

