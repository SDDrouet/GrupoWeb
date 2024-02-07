<?php
// Obtener los datos del cuerpo de la solicitud
$datosJSON = json_decode(file_get_contents('php://input'), true);

if(isset($datosJSON['datos'])) {
    $datos = $datosJSON['datos'];

    // Escribir los datos en un nuevo archivo
    $nombreArchivo = '../temp/proyecciones.json';
    $archivo = fopen($nombreArchivo, 'w');
    if($archivo) {
        fwrite($archivo, json_encode($datos));
        fclose($archivo);
        echo 'Archivo creado exitosamente en: ' . $nombreArchivo;
    } else {
        echo 'Error al crear el archivo.';
    }
} else {
    echo 'No se recibieron datos.';
}
?>



