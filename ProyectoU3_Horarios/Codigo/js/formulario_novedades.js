var formulario = document.getElementById('agregar_novedades');

//Declaracion de variables input
var descripcion_input = document.getElementById('descripcion');

formulario.addEventListener('submit', function(e) {
    if(formulario.checkValidaty() && validarDescripcion()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

function validarDescripcion() {
    var desc = descripcion_input.value;
    var regexDescripcion = /^[A-Za-z0-9\s]+?$/;

    if (!regexDescripcion.test(desc)) {
        descripcion_input.nextElementSibling.innerHTML = 'Por favor, ingresa una descripción válida (letras, números y espacios).';
        descripcion_input.classList.add('is-invalid');
        descripcion_input.classList.remove('is-valid');
        return false;
    } else {
        descripcion_input.nextElementSibling.innerHTML = '';
        descripcion_input.classList.remove('is-invalid');
        descripcion_input.classList.add('is-valid');
        return true;
    }
}

descripcion_input.addEventListener('input', validarDescripcion);
