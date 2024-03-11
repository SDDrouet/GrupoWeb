var formulario = document.getElementById('agregar_aula');

//Declaracion de variables input
var cod_aula_input = document.getElementById('cod_aula');
var capacidad_input = document.getElementById('capacidad');
var bloque_input = document.getElementById('bloque');

formulario.addEventListener('submit', function(e) {
    if(formulario.checkValidity() && validarcod_aula() && validarCapacidad() && validarBloque()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

function validarcod_aula() {
    var cod = cod_aula_input.value;
    var regexID = /^[A-Za-z]{1}\d{3}$/;

        if (!regexID.test(cod)) {
            cod_aula_input.nextElementSibling.innerHTML = 'Por favor, ingresa un ID válido con formato L000.';
            cod_aula_input.classList.add('is-invalid');
            cod_aula_input.classList.remove('is-valid');
            return false;
        } else {
            cod_aula_input.nextElementSibling.innerHTML = '';
            cod_aula_input.classList.remove('is-invalid');
            cod_aula_input.classList.add('is-valid');
            return true;
        }
}

function validarCapacidad() {
    var cap = capacidad_input.value;
    var regexCapacidad = /^[1-9][0-9]?$|^40$/;
    var numero = parseInt(cap, 10);  // Convertir la entrada a un número entero

    if (!regexCapacidad.test(cap) || isNaN(numero) || numero < 1 || numero > 40) {
        capacidad_input.nextElementSibling.innerHTML = 'Por favor, ingresa una capacidad válida (entre 1 y 40).';
        capacidad_input.classList.add('is-invalid');
        capacidad_input.classList.remove('is-valid');
        return false;
    } else {
        capacidad_input.nextElementSibling.innerHTML = '';
        capacidad_input.classList.remove('is-invalid');
        capacidad_input.classList.add('is-valid');
        return true;
    }
}

function validarBloque() {
    var blo = bloque_input.value;
    var regexBloque = /^[A-Za-z]$/;

    if (!regexBloque.test(blo)) {
        bloque_input.nextElementSibling.innerHTML = 'Por favor, ingresa un bloque válido (solo una letra).';
        bloque_input.classList.add('is-invalid');
        bloque_input.classList.remove('is-valid');
        return false;
    } else {
        bloque_input.nextElementSibling.innerHTML = '';
        bloque_input.classList.remove('is-invalid');
        bloque_input.classList.add('is-valid');
        return true;
    }
}

cod_aula_input.addEventListener('input', validarcod_aula);
capacidad_input.addEventListener('input', validarCapacidad);
bloque_input.addEventListener('input', validarBloque);