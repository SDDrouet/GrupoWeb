var formulario = document.getElementById("agregar_docentes");

//Declaracion de variables input
var horas_disponibles_input = document.getElementById("horas_disponibles");
var correo_input = document.getElementById("correo");
var especializacion_input = document.getElementById("especializacion");
var celular_input = document.getElementById("celular");
var cedula_input = document.getElementById("cedula");

formulario.addEventListener("submit", function (e) {
    if (
        formulario.checkValidaty() &&
        validarHoras_disponibles() &&
        validarTipo_contrato() &&
        validarCorreo() &&
        validarEspecializacion() &&
        validarCelular() &&
        validarCedula()
    ) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add("was-validated");
});

//validar 1 o maximo 2 numeros
function validarHoras_disponibles() {
    var horas = horas_disponibles_input.value;
    var regexHoras = /^[0-9]{1,2}$/;

    if (!regexHoras.test(horas)) {
        horas_disponibles_input.nextElementSibling.innerHTML = "Por favor, ingresa un número válido (máximo dos dígitos).";
        horas_disponibles_input.classList.add("is-invalid");
        horas_disponibles_input.classList.remove("is-valid");
        return false;
    } else {
        horas_disponibles_input.nextElementSibling.innerHTML = "";
        horas_disponibles_input.classList.remove("is-invalid");
        horas_disponibles_input.classList.add("is-valid");
        return true;
    }
}

function validarCorreo() {
    var correo = correo_input.value;
    var regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (!regexCorreo.test(correo)) {
        correo_input.nextElementSibling.innerHTML =
            "Por favor, ingresa un correo válido.";
        correo_input.classList.add("is-invalid");
        correo_input.classList.remove("is-valid");
        return false;
    } else {
        correo_input.nextElementSibling.innerHTML = "";
        correo_input.classList.remove("is-invalid");
        correo_input.classList.add("is-valid");
        return true;
    }
}

function validarEspecializacion() {
    var especializacion = especializacion_input.value;
    var regexEspecializacion = /^[A-Za-z\s]+?$/;

    if (!regexEspecializacion.test(especializacion)) {
        especializacion_input.nextElementSibling.innerHTML =
            "Por favor, ingresa una especialización válida (Solo letras).";
        especializacion_input.classList.add("is-invalid");
        especializacion_input.classList.remove("is-valid");
        return false;
    } else {
        especializacion_input.nextElementSibling.innerHTML = "";
        especializacion_input.classList.remove("is-invalid");
        especializacion_input.classList.add("is-valid");
        return true;
    }
}

function validarCelular() {
    var celular = celular_input.value;
    var regexCelular = /^[0-9]{10}$/; // Expresión regular para diez dígitos

    if (!regexCelular.test(celular)) {
        celular_input.nextElementSibling.innerHTML =
            "Por favor, ingresa exactamente diez números.";
        celular_input.classList.add("is-invalid");
        celular_input.classList.remove("is-valid");
        return false;
    } else {
        celular_input.nextElementSibling.innerHTML = "";
        celular_input.classList.remove("is-invalid");
        celular_input.classList.add("is-valid");
        return true;
    }
}

function validarCedula() {
    var cedula = cedula_input.value;
    const regexCedula = /^[0-9]{10}$/;

    // Validar que la cédula tenga 10 dígitos y sean números
    if (!regexCedula.test(cedula)) {
        mostrarError("Por favor, ingresa exactamente diez números.");
        return false;
    }

    // Variables para el algoritmo de módulo 10
    var total = 0,
        individual;

    // Recorrer los 10 dígitos de la cédula
    for (var position = 0; position < 10; position++) {
        // Obtener cada dígito
        individual = cedula.toString().substring(position, position + 1);

        // Si no es un número, la cédula no es válida
        if (isNaN(individual)) {
            mostrarError("El dato solo puede contener números.");
            return false;
        }

        // Aplicar lógica del módulo 10
        if (position < 9) {
            if (position % 2 == 0) {
                total += parseInt(individual) * 2 > 9 ? 1 + ((parseInt(individual) * 2) % 10) : parseInt(individual) * 2;
            } else {
                total += parseInt(individual);
            }
        }
    }

    // Obtener el dígito verificador
    var digitoVerificador =
        total % 10 != 0 ? total - (total % 10) + 10 - total : 0;

    // Validar dígito verificador
    if (digitoVerificador != parseInt(cedula.charAt(9))) {
        mostrarError("La cédula ingresada no es válida.");
        return false;
    }

    // La cédula es válida
    mostrarExito("Cédula válida");
    return true;

    // Función para mostrar mensaje de error
    function mostrarError(mensaje) {
        cedula_input.nextElementSibling.innerHTML = mensaje;
        cedula_input.classList.add("is-invalid");
        cedula_input.classList.remove("is-valid");
    }

    // Función para mostrar mensaje de éxito
    function mostrarExito(mensaje) {
        cedula_input.nextElementSibling.innerHTML = mensaje;
        cedula_input.classList.remove("is-invalid");
        cedula_input.classList.add("is-valid");
    }
}

horas_disponibles_input.addEventListener("input", validarHoras_disponibles);
correo_input.addEventListener("input", validarCorreo);
especializacion_input.addEventListener("input", validarEspecializacion);
celular_input.addEventListener("input", validarCelular);
cedula_input.addEventListener("input", validarCedula);
