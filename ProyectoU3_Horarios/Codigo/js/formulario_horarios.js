var formulario = document.getElementById('agregar_horarios');

//Declaracion de variables input
var hora_inicio_input = document.getElementById('hora_inicio');
var hora_fin_input = document.getElementById('hora_fin');

formulario.addEventListener('submit', function(e) {
    if(formulario.checkValidity() && validarHora_inicio() && validarHora_fin()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

function validarHora_inicio() {
    var hora_inicio = hora_inicio_input.value;
    var regexHora_inicio = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;

        if (!regexHora_inicio.test(hora_inicio)) {
            hora_inicio_input.nextElementSibling.innerHTML = 'Por favor, ingresa una hora válida con formato HH:MM.';
            hora_inicio_input.classList.add('is-invalid');
            hora_inicio_input.classList.remove('is-valid');
            return false;
        } else {
            hora_inicio_input.nextElementSibling.innerHTML = '';
            hora_inicio_input.classList.remove('is-invalid');
            hora_inicio_input.classList.add('is-valid');
            return true;
        }
}

function validarHora_fin() {
    var hora_fin = hora_fin_input.value;
    var regexHora_fin = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;

        if (!regexHora_fin.test(hora_fin)) {
            hora_fin_input.nextElementSibling.innerHTML = 'Por favor, ingresa una hora válida con formato HH:MM.';
            hora_fin_input.classList.add('is-invalid');
            hora_fin_input.classList.remove('is-valid');
            return false;
        } else {
            hora_fin_input.nextElementSibling.innerHTML = '';
            hora_fin_input.classList.remove('is-invalid');
            hora_fin_input.classList.add('is-valid');
            return true;
        }
}

hora_fin_input.addEventListener('input', validarHora_fin);
hora_inicio_input.addEventListener('input', validarHora_inicio);