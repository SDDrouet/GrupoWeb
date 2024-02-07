<?php
function editarRegistro($indice, $nombreArchivo, $nuevaInfo) {
    // Cargar el contenido del archivo JSON
    $contenido_json = file_get_contents($nombreArchivo);

    // Decodificar el contenido JSON en un array asociativo
    $datos = json_decode($contenido_json, true);

    // Actualizar el registro en el índice especificado con la nueva información
    $datos[$indice] = $nuevaInfo;

    // Codificar el array actualizado de nuevo a formato JSON
    $contenido_actualizado = json_encode($datos, JSON_PRETTY_PRINT);

    // Guardar los datos actualizados en el archivo JSON
    file_put_contents($nombreArchivo, $contenido_actualizado);

    // Devolver una respuesta (por ejemplo, un mensaje de éxito)
    echo "Registro editado exitosamente";
}

// Verificar si se recibieron datos por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos enviados por POST
    $dataEnviado = file_get_contents("php://input");
    // Decodificar los datos JSON a un array asociativo
    $data = json_decode($dataEnviado, true);

    // Obtener los valores del array asociativo
    $indice = $data['indice'];
    $nombreArchivo = $data['nombreArchivo'];
    $nuevaInfo = $data['nuevaInfo'];

    // Llamar a la función editarRegistro para editar el registro
    editarRegistro($indice, $nombreArchivo, $nuevaInfo);
} else {
    // Si no se recibieron datos por POST, devolver un error
    http_response_code(400); // Código de error de solicitud incorrecta
    echo "Error: Se esperaban datos por POST";
}
?>