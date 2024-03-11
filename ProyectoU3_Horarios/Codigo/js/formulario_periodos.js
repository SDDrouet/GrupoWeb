var formulario = document.getElementById('agregar_periodo');

//Declaracion de variables input
var nombre_periodo_input = document.getElementById('nombre_periodo');
var fecha_inicio_input = document.getElementById('fecha_inicio');
var fecha_fin_input = document.getElementById('fecha_fin');


formulario.addEventListener('submit', function (e) {
    if (formulario.checkValidaty() && validarNombrePeriodo() && validarFechaInicio() && validarFechaFin() && validarHorario()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

function validarNombrePeriodo() {
    var nombre = nombre_periodo_input.value;
    var regexNombre = /^[A-Za-z0-9\-\s]+$/;

    if (!regexNombre.test(nombre)) {
        nombre_periodo_input.nextElementSibling.innerHTML = 'Por favor, ingresa un nombre válido (letras, números y guiones).';
        nombre_periodo_input.classList.add('is-invalid');
        nombre_periodo_input.classList.remove('is-valid');
        return false;
    } else {
        nombre_periodo_input.nextElementSibling.innerHTML = '';
        nombre_periodo_input.classList.remove('is-invalid');
        nombre_periodo_input.classList.add('is-valid');
        return true;
    }
}

function validarFechaFin() {
    var fechaInicio = fecha_inicio_input.value;
    var fechaFin = fecha_fin_input.value;

    if (fechaInicio === '') {
        return true;
    }

    if (fechaFin <= fechaInicio) {
        fecha_fin_input.nextElementSibling.innerHTML = 'La fecha de fin debe ser mayor a la fecha de inicio.';
        fecha_fin_input.classList.add('is-invalid');
        fecha_fin_input.classList.remove('is-valid');
        return false;
    } else {
        fecha_fin_input.nextElementSibling.innerHTML = '';
        fecha_fin_input.classList.remove('is-invalid');
        fecha_fin_input.classList.add('is-valid');
        return true;
    }
}

function validarFechaInicio() {
    var fechaInicio = fecha_inicio_input.value;
    var fechaFin = fecha_fin_input.value;

    if (fechaFin === '') {
        return true;
    }

    if (fechaInicio >= fechaFin) {
        fecha_inicio_input.nextElementSibling.innerHTML = 'La fecha de inicio debe ser menor a la fecha de fin.';
        fecha_inicio_input.classList.add('is-invalid');
        fecha_inicio_input.classList.remove('is-valid');
        return false;
    } else {
        fecha_inicio_input.nextElementSibling.innerHTML = '';
        fecha_inicio_input.classList.remove('is-invalid');
        fecha_inicio_input.classList.add('is-valid');
        return true;
    }
}

nombre_periodo_input.addEventListener('input', validarNombrePeriodo);
fecha_inicio_input.addEventListener('input', validarFechaInicio);
fecha_fin_input.addEventListener('input', validarFechaFin);


