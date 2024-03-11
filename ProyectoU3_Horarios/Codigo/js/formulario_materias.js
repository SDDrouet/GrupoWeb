var formulario = document.getElementById('agregar_materias');

//Declaracion de variables input
var cod_materia_input = document.getElementById('cod_materia');
var nombre_input = document.getElementById('nombre_materia');
var horas_semana_input = document.getElementById('horas_semana');

formulario.addEventListener('submit', function(e) {
    if(formulario.checkValidity() && validarCod_materia() && validarNombre() && validarHoras_semana()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

//validar codigo letras y numeros
function validarCod_materia() {
    var cod = cod_materia_input.value;
    var regexCod = /^[A-Za-z\d]+?$/;

        if (!regexCod.test(cod)) {
            cod_materia_input.nextElementSibling.innerHTML = 'Por favor, ingresa un código válido.';
            cod_materia_input.classList.add('is-invalid');
            cod_materia_input.classList.remove('is-valid');
            return false;
        } else {
            cod_materia_input.nextElementSibling.innerHTML = '';
            cod_materia_input.classList.remove('is-invalid');
            cod_materia_input.classList.add('is-valid');
            return true;
        }
}

//validar solo letras y espacios
function validarNombre() {
    var nom = nombre_input.value;
    var regexNombre = /^[A-Za-z\s]+?$/;

        if (!regexNombre.test(nom)) {
            nombre_input.nextElementSibling.innerHTML = 'Por favor, ingresa un nombre válido (solo letras).';
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

//validar solo numeros
function validarHoras_semana() {
    var horas = horas_semana_input.value;
    var regexHoras = /^(?:[1-9]|10)$/;
    var numero = parseInt(horas, 10);  // Convertir la entrada a un número entero

    if (!regexHoras.test(horas) || isNaN(numero) || numero < 1 || numero > 10) {
        horas_semana_input.nextElementSibling.innerHTML = 'Por favor, ingresa un número válido (entre 1 y 10).';
        horas_semana_input.classList.add('is-invalid');
        horas_semana_input.classList.remove('is-valid');
        return false;
    } else {
        horas_semana_input.nextElementSibling.innerHTML = '';
        horas_semana_input.classList.remove('is-invalid');
        horas_semana_input.classList.add('is-valid');
        return true;
    }
}

cod_materia_input.addEventListener('input', validarCod_materia);
nombre_input.addEventListener('input', validarNombre);
horas_semana_input.addEventListener('input', validarHoras_semana);