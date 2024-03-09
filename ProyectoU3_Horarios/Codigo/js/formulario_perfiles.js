var formulario = document.getElementById('agregar_perfiles');

//Declaracion de variables input
var tipo_perfil_input = document.getElementById('tipo_perfil');
var privilegios_input = document.getElementById('privilegios');

formulario.addEventListener('submit', function(e) {
    if(formulario.checkValidaty() && validarTipo_perfil() && validarPrivilegios()) {
        return true;
    } else {
        event.preventDefault();
        event.stopPropagation();
    }

    formulario.classList.add('was-validated');
});

function validarTipo_perfil() {
    var tipo = tipo_perfil_input.value;

        if (!/^[A-Za-z]+(?: [A-Za-z]+)?$/.test(tipo)) {
            tipo_perfil_input.nextElementSibling.innerHTML = 'Por favor, ingresa un tipo de perfil válido (solo letras).';
            tipo_perfil_input.classList.add('is-invalid');
            tipo_perfil_input.classList.remove('is-valid');
            return false;
        } else {
            tipo_perfil_input.nextElementSibling.innerHTML = '';
            tipo_perfil_input.classList.remove('is-invalid');
            tipo_perfil_input.classList.add('is-valid');
            return true;
        }
}

function validarPrivilegios() {
    var priv = privilegios_input.value;
    var regexPrivilegios = /^[a-zA-Z ]+$/;

        if (!regexPrivilegios.test(priv)) {
            privilegios_input.nextElementSibling.innerHTML = 'Por favor, ingresa un privilegio valido';
            privilegios_input.classList.add('is-invalid');
            privilegios_input.classList.remove('is-valid');
            return false;
        } else {
            privilegios_input.nextElementSibling.innerHTML = '';
            privilegios_input.classList.remove('is-invalid');
            privilegios_input.classList.add('is-valid');
            return true;
        }
}

privilegios_input.addEventListener('input', validarPrivilegios);
tipo_perfil_input.addEventListener('input', validarTipo_perfil);