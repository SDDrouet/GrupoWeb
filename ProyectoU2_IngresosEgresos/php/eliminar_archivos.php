<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");


function eliminarArchivos() {
    $directorio = "../temp"; // Puedes ajustar esto según tu estructura de archivos
    $archivos = glob($directorio . '/usuario.json');

    foreach ($archivos as $archivo) {
        if (file_exists($archivo)) {
            unlink($archivo);
            echo 'Archivo eliminado: ' . $archivo . '<br>';
        }
    }
}

// Llama a la función para eliminar archivos
eliminarArchivos();

?>
