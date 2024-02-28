
function procesos() {
    enviarFormulario();
    redirectToIndex();
}
function enviarFormulario() {
    const formulario = document.getElementById('frmLogin');
    const datos = {
        nombre: formulario.username.value,
    };
    // Obtener el nombre del archivo externamente
    const nombreArchivo = "../temp/usuario.json"

    // Llamada a la función para enviar datos al servidor
    enviarDatosAlServidor('php/guardar_datos.php', datos, nombreArchivo);
}

var username = document.getElementById('username');
username.addEventListener('input', function (evt) {
    this.setCustomValidity('');
});
username.addEventListener('invalid', function (evt) {
    if (this.validity.valueMissing) {
        this.setCustomValidity('Por favor, ingrese su nombre de usuario');
    }
});

var password = document.getElementById('password');
password.addEventListener('input', function (evt) {
    this.setCustomValidity('');
});
password.addEventListener('invalid', function (evt) {
    if (this.validity.valueMissing) {
        this.setCustomValidity('Por favor, ingrese su contraseña');
    }
});

function validateForm() {
    window.location.href = 'php/index.php';
    return false;
}

window.addEventListener("load", async () => {
    await eliminarArchivos();
});

