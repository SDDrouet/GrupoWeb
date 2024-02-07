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
                //window.location.reload(true);
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

async function enviarDatosAlServidorConjunto(ruta, datos) {
    $.ajax({
        url: ruta, // Cambia 'archivo.php' por la ruta de tu script PHP
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ datos: datos }),
        success: function(response) {
            console.log('Datos enviados exitosamente a PHP.');
        },
        error: function(xhr, status, error) {
            console.error('Error al enviar datos a PHP:', error);
        }
    });
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

async function editarRegistro(nombreArchivo, indice, nuevaInfo) {
    const ruta = '../php/editar_elemento.php'; // Ruta al script PHP

    const datos = { indice: indice,
                    nombreArchivo: nombreArchivo,
                    nuevaInfo: nuevaInfo
                    };

    await enviarDatosAlServidor(ruta, datos, nombreArchivo);

}

async function eliminarRegistro(nombreArchivo, indice) {
    const ruta = '../php/eliminar_elemento.php'; // Ruta al script PHP
    const datos = { indice: indice, nombreArchivo: nombreArchivo }; // Datos a enviar al servidor
    await enviarDatosAlServidor(ruta, datos, nombreArchivo);
}
