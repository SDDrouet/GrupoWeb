// script.js

async function enviarDatosAlServidor(ruta, datos, nombreArchivo) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', ruta, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                // Manejar la respuesta del servidor (opcional)
            } else {
                console.error('Error en la solicitud AJAX:', xhr.status);
            }
        }
    };

    // Agregar el nombre del archivo como un parámetro adicional
    datos.nombreArchivo = nombreArchivo;

    // Convertir el objeto JavaScript a formato JSON y enviarlo en el cuerpo de la solicitud
    xhr.send(JSON.stringify(datos));
}

// Función para eliminar archivos utilizando AJAX
async function eliminarArchivos() {
    // Realizar una solicitud AJAX al servidor
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/eliminar_archivos.php', true);
    xhr.send();

    // Manejar la respuesta (opcional)
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };
}
