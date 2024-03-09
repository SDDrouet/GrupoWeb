var formulario = document.getElementById('agregar_usuario');

//Declaracion de variables input
var id_usuario_input = document.getElementById('cod_usuario');
var nombre_input = document.getElementById('nombre');
var apellido = document.getElementById('apellido');
var usuario = document.getElementById('usuario');
var clave = document.getElementById('clave');

formulario.addEventListener('submit', function(e) {
    if(formulario.checkValidaty() && validarID_usuario() && validarNombre() && validarApellido() && validarUsuario() && validarClave()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

function validarID_usuario() {
    var id = id_usuario_input.value;

        if (!/L\d{8}/.test(id)) {
            id_usuario_input.nextElementSibling.innerHTML = 'Por favor, ingresa un ID válido con formato L00000000.';
            id_usuario_input.classList.add('is-invalid');
            id_usuario_input.classList.remove('is-valid');
            return false;
        } else {
            id_usuario_input.nextElementSibling.innerHTML = '';
            id_usuario_input.classList.remove('is-invalid');
            id_usuario_input.classList.add('is-valid');
            return true;
        }
}

function validarNombre() {
    var nom = nombre_input.value;
    var regexNombre = /^[A-Za-z]+(?: [A-Za-z]+)?$/;

        if (!regexNombre.test(nom)) {
            nombre_input.nextElementSibling.innerHTML = 'Por favor, ingresa un nombre válido (solo letras y un espacio opcional).';
            nombre_input.classList.add('is-invalid');
            nombre_input.classList.remove('is-valid');
            return false;
        } else {
            nombre_input.nextElementSibling.innerHTML = '';
            nombre_input.classList.remove('is-invalid');
            nombre_input.classList.add('is-valid');
            return true;
        }
}

function validarApellido() {
    var ape = apellido.value;
    var regexApellido = /^[A-Za-z]+(?: [A-Za-z]+)?$/;

        if (!regexApellido.test(ape)) {
            apellido.nextElementSibling.innerHTML = 'Por favor, ingresa un apellido válido (solo letras y un espacio opcional).';
            apellido.classList.add('is-invalid');
            apellido.classList.remove('is-valid');
            return false;
        } else {
            apellido.nextElementSibling.innerHTML = '';
            apellido.classList.remove('is-invalid');
            apellido.classList.add('is-valid');
            return true;
        }
}

function validarUsuario() {
    var user = usuario.value;

        if (user.length < 4) {
            usuario.nextElementSibling.innerHTML = 'Por favor, ingresa un usuario válido (mínimo 4 caracteres).';
            usuario.classList.add('is-invalid');
            usuario.classList.remove('is-valid');
            return false;
        } else {
            usuario.nextElementSibling.innerHTML = '';
            usuario.classList.remove('is-invalid');
            usuario.classList.add('is-valid');
            return true;
        }
}

function validarClave() {
    var pass = clave.value;
    var regexNumero = /\d/;
    var regexMayuscula = /[A-Z]/;
    var regexMinuscula = /[a-z]/;

    var errores = [];

    if(!regexNumero.test(pass)) {
        errores.push('La contraseña debe contener al menos un número.');
    }

    if(!regexMayuscula.test(pass)) {
        errores.push('Debe contener al menos una letra mayúscula.');
    }

    if(!regexMinuscula.test(pass)) {
        errores.push('Debe contener al menos una letra minúscula.');
    }

    if(pass.length < 8) {
        errores.push('Debe contener al menos 8 caracteres.');
    }

    if(errores.length > 0) {
        var mensajeError = '<ul>';
        errores.forEach(function(error) {
            mensajeError += '<li>' + error + '</li>';
        });
        mensajeError += '</ul>';
        clave.nextElementSibling.innerHTML = mensajeError;
        clave.classList.add('is-invalid');
        return false;
    } else {
        clave.nextElementSibling.innerHTML = '';
        clave.classList.remove('is-invalid');
        clave.classList.add('is-valid');
        return true;
    }   
}

id_usuario_input.addEventListener('input', validarID_usuario);
nombre.addEventListener('input', validarNombre);
apellido.addEventListener('input', validarApellido);
usuario.addEventListener('input', validarUsuario);
clave.addEventListener('input', validarClave);