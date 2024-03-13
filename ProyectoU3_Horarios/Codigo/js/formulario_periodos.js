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
    var fechaInicio = document.getElementById('fecha_inicio').value;
    var fechaFin = document.getElementById('fecha_fin').value;

    if (fechaFin === '') {
        document.getElementById('fecha_fin').classList.add('is-invalid');
        document.getElementById('fecha_fin').nextElementSibling.innerHTML = 'La fecha de fin es requerida.';
        return false;
    } else {
        var fechaInicioObj = new Date(fechaInicio);
        var fechaFinObj = new Date(fechaFin);
        
        if (isNaN(fechaFinObj.getTime())) {
            document.getElementById('fecha_fin').classList.add('is-invalid');
            document.getElementById('fecha_fin').nextElementSibling.innerHTML = 'Por favor, ingrese una fecha válida.';
            return false;
        } else if (fechaFinObj < fechaInicioObj) {
            document.getElementById('fecha_fin').classList.add('is-invalid');
            document.getElementById('fecha_fin').nextElementSibling.innerHTML = 'La fecha de fin no puede ser menor que la fecha de inicio.';
            return false;
        } else {
            document.getElementById('fecha_fin').classList.remove('is-invalid');
            document.getElementById('fecha_fin').classList.add('is-valid');
            document.getElementById('fecha_fin').nextElementSibling.innerHTML = '';
            return true;
        }
    }
}

function validarFechaInicio() {
    var fechaInicio = document.getElementById('fecha_inicio').value;

    if (fechaInicio === '') {
        // Mostrar un mensaje de error indicando que la fecha de inicio es requerida
        document.getElementById('fecha_inicio').classList.add('is-invalid');
        document.getElementById('fecha_inicio').nextElementSibling.innerHTML = 'La fecha de inicio es requerida.';
        return false;
    } else {
        var fechaInicioObj = new Date(fechaInicio);
        
        if (isNaN(fechaInicioObj.getTime())) {
            // Mostrar un mensaje de error indicando que la fecha no es válida
            document.getElementById('fecha_inicio').classList.add('is-invalid');
            document.getElementById('fecha_inicio').nextElementSibling.innerHTML = 'Por favor, ingrese una fecha válida.';
            return false;
        } else {
            // La fecha es válida, quitar clases de error y mostrar mensaje vacío
            document.getElementById('fecha_inicio').classList.remove('is-invalid');
            document.getElementById('fecha_inicio').classList.add('is-valid');
            document.getElementById('fecha_inicio').nextElementSibling.innerHTML = '';
            return true;
        }
    }
}

nombre_periodo_input.addEventListener('input', validarNombrePeriodo);
fecha_inicio_input.addEventListener('input', validarFechaInicio);
fecha_fin_input.addEventListener('input', validarFechaFin);