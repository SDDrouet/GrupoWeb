<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_docente = $_POST['id_docente'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $horas_disponibles = $_POST['horas_disponibles'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $correo = $_POST['correo'];
    $nivel_educacion = $_POST['nivel_educacion'];
    $especializacion = $_POST['especializacion'];
    $celular = $_POST['celular'];
    $cedula = $_POST['cedula'];

    // Formulario para editar docente
    echo "<h2>Editar Docente</h2>";
    echo "<form action='actualizar_docente.php' method='post'>";
    echo "<input type='hidden' name='id_docente' value='$id_docente'>";
    echo "Nombres: <input type='text' name='nombres' value='$nombres'><br>";
    echo "Apellidos: <input type='text' name='apellidos' value='$apellidos'><br>";
    echo "Horas Disponibles: <input type='number' name='horas_disponibles' value='$horas_disponibles'><br>";
    echo "Tipo Contrato: <input type='text' name='tipo_contrato' value='$tipo_contrato'><br>";
    echo "Correo: <input type='email' name='correo' value='$correo'><br>";
    echo "Nivel Educación: <input type='text' name='nivel_educacion' value='$nivel_educacion'><br>";
    echo "Especialización: <input type='text' name='especializacion' value='$especializacion'><br>";
    echo "Celular: <input type='text' name='celular' value='$celular'><br>";
    echo "Cedula: <input type='text' name='cedula' value='$cedula'><br>";
    echo "<input type='submit' value='Actualizar'>";
    echo "</form>";
}

$conn->close();
?>
