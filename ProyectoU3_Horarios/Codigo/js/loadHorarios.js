document.addEventListener("DOMContentLoaded", function () {
    let dias = document.querySelectorAll('.dia');
    dias.forEach(dia => {
        for (let i = 1; i <= 7; i++) {
            let horario = document.createElement('div');
            horario.classList.add('horario');
            dia.appendChild(horario);
        }
    });

    trash = document.getElementById("trash");
    horarios = document.querySelectorAll('.horario');

    cargarPeriodos();
    cargarAulas();
});

document.getElementById('cursos').addEventListener('change', function () {
    var selectedCurso = this.value;
    nCurso = cambiarCurso(selectedCurso);
    dropManager(nCurso);
});

function cambiarCurso(selectedCurso) {
    var cursosContainer = document.getElementById('cursosContainer');

    // Limpiar el contenido actual del contenedor de cursos
    cursosContainer.innerHTML = '';

    // Separar el texto por el carácter "|"
    var info = selectedCurso.split(" | ");
    console.log(selectedCurso);
    let nrc = info[0];
    let materia = info[1];
    let docente = info[2];

    // Crear un nuevo elemento con el curso seleccionado y agregarlo al contenedor
    var nCurso = document.createElement('div');
    nCurso.classList.add("curso");
    nCurso.style.backgroundColor = seleccionarColorPorNRC(nrc);

    // Establecer el contenido HTML del nuevo div
    nCurso.innerHTML = '<div class="cursoSiempre">NRC: <span class="nrc">' + nrc + '</span></div>' +
        '<div class="cursoOcultar">' +
        '<div><strong>Materia: </strong><div>' + materia + '</div></div>' +
        '<br>' +
        '<div><strong>Docente: </strong><div>' + docente + '</div></div>' +
        '</div>';

    nCurso.setAttribute("draggable", "true");
    
    cursosContainer.appendChild(nCurso);

    return nCurso;
}

// Función para seleccionar un color de la paleta basado en el NRC
function seleccionarColorPorNRC(nrc) {
    var paleta = [
        "#7F7F7F",
        "#7987AB",
        "#96A69A",
        "#B5AC91",
        "#A68B8E",
        "#BBA6AB",
        "#B39D84",
        "#A89289",
        "#9B847A",
        "#7E827A",
        "#858585",
        "#9C948A",
        "#8C7B7B",
        "#7F7272",
        "#898989",
        "#7D868D",
        "#7D9B89",
        "#96A17F",
        "#9F9A83",
        "#A59E8E"
      ];
    // Convertir el NRC a un número
    var seed = nrc.split('').reduce(function (acc, char) {
        return acc + char.charCodeAt(0);
    }, 0);

    // Generar un número aleatorio entre 0 y 19 usando el NRC como semilla
    var indice = Math.floor(Math.abs(Math.sin(seed) * 10000)) % 20;

    // Obtener el color correspondiente al índice generado
    return paleta[indice];
}

function cargarAulas() {
    var xhr = new XMLHttpRequest();
    
    xhr.open("GET", "getOptionsAulasGH.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                var select = document.getElementById("aula");
                
                // Limpiar el select
                select.innerHTML = "";
                
                // Agregar las opciones devueltas por la consulta
                data.forEach(function(aula) {
                    var option = document.createElement("option");
                    let aulaList = aula.split(",");
                    let idAula = aulaList[0];
                    let nombreAula = aulaList[1];
                    option.value = idAula;
                    option.classList.add("opcion")
                    option.textContent = nombreAula;
                    select.appendChild(option);
                });

            } else {
                console.error("Error al realizar la solicitud:", xhr.status);
            }
        }
    };
    
    xhr.send();
}

function cargarPeriodos() {
    var xhr = new XMLHttpRequest();
    
    xhr.open("GET", "getOptionsPeriodosGH.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                var select = document.getElementById("periodo");
                
                // Limpiar el select
                select.innerHTML = "";
                
                // Agregar las opciones devueltas por la consulta
                data.forEach(function(periodo) {
                    var option = document.createElement("option");
                    let periodoList = periodo.split(",");
                    let idPeriodo = periodoList[0];
                    let nombrePeriodo = periodoList[1];
                    option.value = idPeriodo;
                    option.classList.add("opcion")
                    option.textContent = nombrePeriodo;
                    select.appendChild(option);
                });

                let idPeriodo = select.options[0].value;
                cargarCursos(idPeriodo);

            } else {
                console.error("Error al realizar la solicitud:", xhr.status);
            }
        }
    };
    
    xhr.send();
}

function cargarCursos(idPeriodo) {
    var xhr = new XMLHttpRequest();
    
    xhr.open("GET", "getOptionsCursosGH.php?idPeriodo=" + idPeriodo, true); // Pasar el ID del período en la URL
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                var select = document.getElementById("cursos");
                
                // Limpiar el select
                select.innerHTML = "";
                
                // Agregar las opciones devueltas por la consulta
                data.forEach(function(curso) {
                    var option = document.createElement("option");
                    option.value = curso;
                    option.classList.add("opcion")
                    option.textContent = curso;
                    select.appendChild(option);
                });

                // Llamar a dropManager después de cargar los cursos
                if (select.options.length > 0) {
                    var primerCurso = select.options[0].value;
                    var nCurso = cambiarCurso(primerCurso);
                    dropManager(nCurso);
                } else {
                    var cursosContainer = document.getElementById('cursosContainer');
                    cursosContainer.innerHTML = '';
                }

            } else {
                console.error("Error al realizar la solicitud:", xhr.status);
            }
        }
    };
    
    xhr.send();
}


document.getElementById('periodo').addEventListener('change', function () {
    var idPeriodo = this.value; // Obtener el valor del periodo seleccionado
    cargarCursos(idPeriodo);
});