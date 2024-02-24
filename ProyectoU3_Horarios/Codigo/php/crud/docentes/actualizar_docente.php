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

    $sql = "UPDATE docentes SET 
            nombres='$nombres', 
            apellidos='$apellidos', 
            horas_disponibles=$horas_disponibles, 
            tipo_contrato='$tipo_contrato', 
            correo='$correo', 
            nivel_educacion='$nivel_educacion', 
            especializacion='$especializacion', 
            celular='$celular', 
            cedula='$cedula' 
            WHERE id_docente='$id_docente'";

    if ($conn->query($sql) === TRUE) {
        header("Location: docentes.php");
        echo "Docente actualizado exitosamente";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

$conn->close();
?>
