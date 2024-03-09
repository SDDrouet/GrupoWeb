var formulario = document.getElementById('agregar_docentes');

//Declaracion de variables input
var id_docente_input = document.getElementById('id_docente');
var nombre_input = document.getElementById('nombres');
var apellido_input = document.getElementById('apellidos');
var horas_disponibles_input = document.getElementById('horas_disponibles');
var correo_input = document.getElementById('correo');
var nivel_educacion_input = document.getElementById('nivel_educacion');
var especializacion_input = document.getElementById('especializacion');
var celular_input = document.getElementById('celular');
var cedula_input = document.getElementById('cedula');

formulario.addEventListener('submit', function (e) {
    if (formulario.checkValidaty() && validarID_docente() && validarNombre() && validarApellido() && validarHoras_disponibles() && validarTipo_contrato() && validarCorreo() && validarNivel_educacion() && validarEspecializacion() && validarCelular() && validarCedula()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

function validarID_docente() {
    var id = id_docente_input.value;

    if (!/L\d{8}/.test(id)) {
        id_docente_input.nextElementSibling.innerHTML = 'Por favor, ingresa un ID válido con formato L00000000.';
        id_docente_input.classList.add('is-invalid');
        id_docente_input.classList.remove('is-valid');
        return false;
    } else {
        id_docente_input.nextElementSibling.innerHTML = '';
        id_docente_input.classList.remove('is-invalid');
        id_docente_input.classList.add('is-valid');
        return true;
    }
}

function validarNombre() {
    var nombres = nombre_input.value;
    var regexNombre = /^[A-Za-z]+(?: [A-Za-z]+)?$/;

    if (!regexNombre.test(nombres)) {
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
    var apellidos = apellido_input.value;
    var regexApellido = /^[A-Za-z]+(?: [A-Za-z]+)?$/;

    if (!regexApellido.test(apellidos)) {
        apellido_input.nextElementSibling.innerHTML = 'Por favor, ingresa un apellido válido (solo letras y un espacio opcional).';
        apellido_input.classList.add('is-invalid');
        apellido_input.classList.remove('is-valid');
        return false;
    } else {
        apellido_input.nextElementSibling.innerHTML = '';
        apellido_input.classList.remove('is-invalid');
        apellido_input.classList.add('is-valid');
        return true;
    }
}

function validarHoras_disponibles() {
    var horas = horas_disponibles_input.value;
    var regexHoras = /^[0-9]{2}$/;

    if (!regexHoras.test(horas)) {
        horas_disponibles_input.nextElementSibling.innerHTML = 'Por favor, ingresa exactamente dos números.';
        horas_disponibles_input.classList.add('is-invalid');
        horas_disponibles_input.classList.remove('is-valid');
        return false;
    } else {
        horas_disponibles_input.nextElementSibling.innerHTML = '';
        horas_disponibles_input.classList.remove('is-invalid');
        horas_disponibles_input.classList.add('is-valid');
        return true;
    }
}

function validarCorreo() {
    var correo = correo_input.value;
    var regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (!regexCorreo.test(correo)) {
        correo_input.nextElementSibling.innerHTML = 'Por favor, ingresa un correo válido.';
        correo_input.classList.add('is-invalid');
        correo_input.classList.remove('is-valid');
        return false;
    } else {
        correo_input.nextElementSibling.innerHTML = '';
        correo_input.classList.remove('is-invalid');
        correo_input.classList.add('is-valid');
        return true;
    }
}

function validarNivel_educacion() {
    var nivel = nivel_educacion_input.value;
    var regexNivel = /^[A-Za-z]+(?: [A-Za-z]+)?$/;

    if (!regexNivel.test(nivel)) {
        nivel_educacion_input.nextElementSibling.innerHTML = 'Por favor, ingresa un nivel de educación válido (solo letras y un espacio opcional).';
        nivel_educacion_input.classList.add('is-invalid');
        nivel_educacion_input.classList.remove('is-valid');
        return false;
    } else {
        nivel_educacion_input.nextElementSibling.innerHTML = '';
        nivel_educacion_input.classList.remove('is-invalid');
        nivel_educacion_input.classList.add('is-valid');
        return true;
    }
}

function validarEspecializacion() {
    var especializacion = especializacion_input.value;
    var regexEspecializacion = /^[A-Za-z]+(?: [A-Za-z]+)?$/;

    if (!regexEspecializacion.test(especializacion)) {
        especializacion_input.nextElementSibling.innerHTML = 'Por favor, ingresa una especialización válida (solo letras y un espacio opcional).';
        especializacion_input.classList.add('is-invalid');
        especializacion_input.classList.remove('is-valid');
        return false;
    } else {
        especializacion_input.nextElementSibling.innerHTML = '';
        especializacion_input.classList.remove('is-invalid');
        especializacion_input.classList.add('is-valid');
        return true;
    }
}

function validarCelular() {
    var celular = celular_input.value;
    var regexCelular = /^[0-9]{10}$/; // Expresión regular para diez dígitos

    if (!regexCelular.test(celular)) {
        celular_input.nextElementSibling.innerHTML = 'Por favor, ingresa exactamente diez números.';
        celular_input.classList.add('is-invalid');
        celular_input.classList.remove('is-valid');
        return false;
    } else {
        celular_input.nextElementSibling.innerHTML = '';
        celular_input.classList.remove('is-invalid');
        celular_input.classList.add('is-valid');
        return true;
    }
}

function validarCedula() {
    var cedula = cedula_input.value;
    var regexCedula = /^[0-9]{10}$/; // Expresión regular para diez dígitos

    if (!regexCedula.test(cedula)) {
        cedula_input.nextElementSibling.innerHTML = 'Por favor, ingresa exactamente diez números.';
        cedula_input.classList.add('is-invalid');
        cedula_input.classList.remove('is-valid');
        return false;
    } else {
        cedula_input.nextElementSibling.innerHTML = '';
        cedula_input.classList.remove('is-invalid');
        cedula_input.classList.add('is-valid');
        return true;
    }
}

id_docente_input.addEventListener('input', validarID_docente);
nombre_input.addEventListener('input', validarNombre);
apellido_input.addEventListener('input', validarApellido);
horas_disponibles_input.addEventListener('input', validarHoras_disponibles);
correo_input.addEventListener('input', validarCorreo);
nivel_educacion_input.addEventListener('input', validarNivel_educacion);
especializacion_input.addEventListener('input', validarEspecializacion);
celular_input.addEventListener('input', validarCelular);
cedula_input.addEventListener('input', validarCedula);