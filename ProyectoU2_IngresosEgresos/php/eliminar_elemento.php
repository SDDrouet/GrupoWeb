<?php
// Verificar si se recibieron datos por POST
$dataEnviado = file_get_contents("php://input");

// Decodificar datos JSON a un array asociativo
$data = json_decode($dataEnviado, true);

// Obtener el índice del elemento a eliminar y el nombre del archivo
$indice = $data['indice'];
$nombreArchivo = $data['nombreArchivo'];

// Cargar el contenido del archivo JSON
$contenido_json = file_get_contents($nombreArchivo);

// Decodificar el contenido JSON en un array asociativo
$datos = json_decode($contenido_json, true);

// Eliminar el elemento del array utilizando el índice
unset($datos[$indice]);

// Codificar el array actualizado de nuevo a formato JSON
$contenido_actualizado = json_encode(array_values($datos), JSON_PRETTY_PRINT);

// Guardar los datos actualizados en el archivo JSON
file_put_contents($nombreArchivo, $contenido_actualizado);

?>
