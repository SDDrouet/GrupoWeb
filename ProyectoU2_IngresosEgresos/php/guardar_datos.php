<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");




function agregarDatosAJSON($rutaArchivo, $nuevosDatos) {
    // Verificar si el archivo existe
    if (file_exists($rutaArchivo)) {
        // Leer contenido actual del archivo
        $contenidoActual = file_get_contents($rutaArchivo);

        // Decodificar el contenido JSON actual
        $datosExistente = json_decode($contenidoActual, true);

        // Agregar nuevos datos a los existentes
        $datosExistente[] = $nuevosDatos;

        // Convertir a JSON
        $jsonDatos = json_encode($datosExistente, JSON_PRETTY_PRINT);

        // Escribir el JSON en el archivo
        file_put_contents($rutaArchivo, $jsonDatos);

        echo 'Datos agregados al archivo JSON exitosamente.';
    } else {
        // Si el archivo no existe, crearlo y agregar los datos del formulario
        $jsonDatos = json_encode(array($nuevosDatos), JSON_PRETTY_PRINT);

        // Escribir el JSON en el archivo
        file_put_contents($rutaArchivo, $jsonDatos);

        echo 'Archivo JSON creado y datos agregados exitosamente.';
    }
}

// Obtener datos del cuerpo de la solicitud
$data = file_get_contents("php://input");

// Decodificar datos JSON a un array asociativo
$datosFormulario = json_decode($data, true);

// Acceder al nombre del archivo
$nombreArchivo = $datosFormulario['nombreArchivo'];

// Eliminar la clave 'nombreArchivo' del array
unset($datosFormulario['nombreArchivo']);

// Llamada a la función
agregarDatosAJSON($nombreArchivo, $datosFormulario);
?>


