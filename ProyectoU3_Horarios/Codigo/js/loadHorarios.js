function updateHorariosCalendario() {
    horarios = document.querySelectorAll('.horario');
}

document.addEventListener("DOMContentLoaded", function () {
    trash = document.getElementById("trash");
    cargarPeriodos();
    cargarAulas();
});

document.getElementById('cursos').addEventListener('change', function () {
    var selectedCurso = this.value;
    let primerCursoArr = selectedCurso.split(";");
    let idCurso = primerCursoArr[0];
    let infoCurso = primerCursoArr[1];
    cambiarCurso(infoCurso, idCurso);
});

function crearCurso(selectedCurso, idCurso) {
    // Separar el texto por el carácter "|"
    var info = selectedCurso.split(" | ");
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
        '</div>'+
        '<div class="IDOculto">' + idCurso + '</div>';

    nCurso.setAttribute("draggable", "true");

    dropManager(nCurso);

    return nCurso;
}

function cambiarCurso(selectedCurso, idCurso) {
    var cursosContainer = document.getElementById('cursosContainer');

    // Limpiar el contenido actual del contenedor de cursos
    cursosContainer.innerHTML = '';

    let nCurso = crearCurso(selectedCurso, idCurso);

    cursosContainer.appendChild(nCurso);
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
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                var select = document.getElementById("aula");

                // Limpiar el select
                select.innerHTML = "";

                // Agregar las opciones devueltas por la consulta
                data.forEach(function (aula) {
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
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                var select = document.getElementById("periodo");

                // Limpiar el select
                select.innerHTML = "";

                // Agregar las opciones devueltas por la consulta
                data.forEach(function (periodo) {
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
                //cargarCalendarioSemanal(idPeriodo);

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
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                var select = document.getElementById("cursos");

                // Limpiar el select
                select.innerHTML = "";

                let cursos = data.cursos;
                let idsCursos = data.id_cursos

                for (let i = 0; i < cursos.length; i++) {
                    let curso = cursos[i];
                    let idCurso = idsCursos[i];

                    var option = document.createElement("option");
                    option.value = idCurso + ";" + curso;
                    option.classList.add("opcion")
                    option.textContent = curso;
                    select.appendChild(option);

                }

                // Llamar a dropManager después de cargar los cursos
                if (select.options.length > 0) {
                    var idAula = document.getElementById('aula').value;
                    var idPeriodo = document.getElementById('periodo').value;
                    cargarCalendarioSemanal(idPeriodo, idAula);
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
    var idAula = document.getElementById('aula').value;
    cargarCalendarioSemanal(idPeriodo, idAula);
    cargarCursos(idPeriodo);
});

document.getElementById('aula').addEventListener('change', function () {
    var idAula = this.value; // Obtener el valor del periodo seleccionado
    var idPeriodo = document.getElementById('periodo').value;
    cargarCalendarioSemanal(idPeriodo, idAula)
    //obtenerCursosHorarios(idPeriodo, idAula);
});


function obtenerHorariosAulasCursos(idPeriodo, idAula) {
    var xhr = new XMLHttpRequest();

    xhr.open("GET", "getOptionsHACursosGH.php?idPeriodo=" + idPeriodo + "&idAula=" + idAula, true); // Pasar el ID del período en la URL
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                let cursos = data.cursos;
                let horarios = data.horarios;
                let idCursos = data.id_cursos
                actulizarCalendarioSemanalCursos(cursos, horarios, idCursos);


            } else {
                console.error("Error al realizar la solicitud:", xhr.status);
            }
        }
    };

    xhr.send();
}


function actulizarCalendarioSemanalCursos(cursos, horarios, idCursos) {
    // Iterar sobre los arrays de cursos y horarios
    for (var i = 0; i < cursos.length; i++) {
        var cursoText = cursos[i];
        var horario = horarios[i];
        var idCurso = idCursos[i];

        let cajaHorario = document.getElementById(horario);
        let nCurso = crearCurso(cursoText, idCurso);
        cajaHorario.appendChild(nCurso);
    }
}


function cargarCalendarioSemanal(idPeriodo, idAula) {
    var xhr = new XMLHttpRequest();

    xhr.open("GET", "getOptionsFHorariosGH.php?idPeriodo=" + idPeriodo + "&idAula=" + idAula, true); // Pasar el ID del período en la URL
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                actualizarCalendarioSemanal(data);
            } else {
                console.error("Error al realizar la solicitud:", xhr.status);
            }
        }
    };

    xhr.send();
}

function actualizarCalendarioSemanal(dataHorario) {
    let id_horarios = dataHorario.id_horario;
    let dias = dataHorario.dia;
    let horas_inicio = dataHorario.hora_inicio;

    refrescarCalendario();

    for (let i = 0; i < id_horarios.length; i++) {
        let idHorario = id_horarios[i];
        let dia = dias[i];
        let horasInicio = horas_inicio[i].split(':')[0];

        let horario = document.getElementById(dia + horasInicio);
        if (horario) {
            horario.id = 'id_hr' + idHorario;
            horario.classList.remove('horarioInvalido');
            horario.classList.add('horario');
        }
    }
    updateHorariosCalendario();

    var primerCurso = document.getElementById("cursos").options[0].value;
    let primerCursoArr = primerCurso.split(";");
    let idCurso = primerCursoArr[0];
    let infoCurso = primerCursoArr[1];
    cambiarCurso(infoCurso, idCurso);
    var idAula = document.getElementById('aula').value;
    var idPeriodo = document.getElementById('periodo').value;
    obtenerHorariosAulasCursos(idPeriodo, idAula);

}

function refrescarCalendario() {
    let franjasPlantilla = ['07', '09', '11', '13', '15', '17', '19'];

    let diasContenedor = document.querySelectorAll('.dia');
    diasContenedor.forEach(diaContenedor => {
        diaContenedor.innerHTML = "";
        for (let i = 0; i < 7; i++) {
            let horario = document.createElement('div');
            horario.id = diaContenedor.id + franjasPlantilla[i];
            horario.classList.add('horarioInvalido');
            diaContenedor.appendChild(horario);
        }
    });
}
