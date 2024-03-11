var formulario_carrera = document.getElementById('agregar_carrera');

//Declaracion de variables input
var nombre_carrera_input = document.getElementById('nombre_carrera');

formulario_carrera.addEventListener('submit', function(e) {
    if(formulario_carrera.checkValidaty() && validarNombreCarrera()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario_carrera.classList.add('was-validated');
}
);

function validarNombreCarrera() {
    var nombre = nombre_carrera_input.value;
    var regexNombreCarrera = /^[A-Za-z\s]+?$/;

        if (!regexNombreCarrera.test(nombre)) {
            nombre_carrera_input.nextElementSibling.innerHTML = 'Por favor, ingresa un nombre válido (solo letras).';
            nombre_carrera_input.classList.add('is-invalid');
            nombre_carrera_input.classList.remove('is-valid');
            return false;
        } else {
            nombre_carrera_input.nextElementSibling.innerHTML = '';
            nombre_carrera_input.classList.remove('is-invalid');
            nombre_carrera_input.classList.add('is-valid');
            return true;
        }
}

nombre_carrera_input.addEventListener('input', validarNombreCarrera);