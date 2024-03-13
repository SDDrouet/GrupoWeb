document.addEventListener("DOMContentLoaded", function () {
    cargarPeriodos();
});

function crearCurso(nrc, nombre_materia, cod_aula) {
    
    // Crear un nuevo elemento con el curso seleccionado y agregarlo al contenedor
    var nCurso = document.createElement('div');
    nCurso.classList.add("curso1");
    nCurso.style.backgroundColor = seleccionarColorPorNRC(nrc);

    // Establecer el contenido HTML del nuevo div
    nCurso.innerHTML = '<div class="cursoSiempre"><strong>NRC: </strong><span class="nrc">' + nrc + '</span>' +
        '<div><strong>Materia: </strong><div>' + nombre_materia + '</div></div>' +
        '<div><strong>Aula: </strong>' + cod_aula + '</div>' +
        '</div>';
    
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

function cargarDocentes(idPeriodo) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getOptionsHorariosDocentesR.php?id_periodo=" + idPeriodo, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                var select = document.getElementById("docente");

                let id_docentes = data.id_docente;
                let nonbres = data.nombre;

                // Limpiar el select
                select.innerHTML = "";

                for (let i = 0; i < id_docentes.length; i++) {
                    var option = document.createElement("option");
                    option.value = id_docentes[i];
                    option.classList.add("opcion")
                    option.textContent = nonbres[i];
                    select.appendChild(option);
                }
                
                cargarCalendarioSemanal(idPeriodo, id_docentes[0]);


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
                cargarDocentes(idPeriodo);                

            } else {
                console.error("Error al realizar la solicitud:", xhr.status);
            }
        }
    };

    xhr.send();
}

document.getElementById('periodo').addEventListener('change', function () {
    var idPeriodo = this.value; // Obtener el valor del periodo seleccionado
    var idDocente = document.getElementById('docente').value;
    cargarCalendarioSemanal(idPeriodo, idDocente);
});

document.getElementById('docente').addEventListener('change', function () {
    var idDocente = this.value; // Obtener el valor del periodo seleccionado
    var idPeriodo = document.getElementById('periodo').value;
    cargarCalendarioSemanal(idPeriodo, idDocente);
});

function cargarCalendarioSemanal(idPeriodo, idDocente) {
    var xhr = new XMLHttpRequest();

    xhr.open("GET", "getOptionsFHorariosDocentesGH.php?idPeriodo=" + idPeriodo + "&idDocente=" + idDocente, true); // Pasar el ID del período en la URL
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                
                console.log("CASRA");
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
    let nrc = dataHorario.nrc;
    let nombre_materia = dataHorario.nombre_materia;
    let cod_aula = dataHorario.cod_aula;

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
            horario.appendChild(crearCurso(nrc[i], nombre_materia[i], cod_aula[i]));
        }
    }
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

