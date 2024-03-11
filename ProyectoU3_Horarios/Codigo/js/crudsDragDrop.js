function createHorario(horarioA, horarioB, cursoA) {
    let idCursoA = cursoA.querySelector('.IDOculto').textContent;
    let idHorarioAulaA = horarioA.id;
    let idHorarioAulaB = horarioB.id;
    idHorarioAulaA = idHorarioAulaA.replace("id_hr", "");
    idHorarioAulaB = idHorarioAulaB.replace("id_hr", "");

    if (idHorarioAulaA !== "cursosContainer") {
        createUpdateHorarioAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA);
        horarioB.appendChild(cursoA);
    } else {
        createHorarioAJAX(idHorarioAulaB, idCursoA);
        horarioB.appendChild(cursoA);
    }

}

function updateHorarios(horarioA, horarioB, cursoA, cursoB) {
    let idCursoA = cursoA.querySelector('.IDOculto').textContent;
    let idCursoB = cursoB.querySelector('.IDOculto').textContent;
    let idHorarioAulaA = horarioA.id;
    let idHorarioAulaB = horarioB.id;
    idHorarioAulaA = idHorarioAulaA.replace("id_hr", "");
    idHorarioAulaB = idHorarioAulaB.replace("id_hr", "");
    /*
    if (idHorarioAulaA !== "cursosContainer") {
        if (idCursoA !== idCursoB) {

            updateHorariosAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA, idCursoB);

            horarioB.removeChild(cursoB);
            horarioA.appendChild(cursoB);
            horarioA.removeChild(cursoA);
            horarioB.appendChild(cursoA);
        }
    }*/

}

function deleteHorario(horarioA, cursoA) {
    let idCursoA = cursoA.querySelector('.IDOculto').textContent;
    let idHorarioAulaA = horarioA.id;
    idHorarioAulaA = idHorarioAulaA.replace("id_hr", "");

    if (idHorarioAulaA !== "cursosContainer") {
        deleteHorarioAJAX(idHorarioAulaA, idCursoA);
        horarioA.removeChild(cursoA);
    }
}

function createUpdateHorarioAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA) {
    console.log("idCursoA: " + idCursoA);
    console.log("idHorarioAulaA: " + idHorarioAulaA);
    console.log("idHorarioAulaB: " + idHorarioAulaB);

    // Crear un objeto FormData para enviar los datos al archivo PHP
    let formData = new FormData();
    formData.append('idCursoA', idCursoA);
    formData.append('idHorarioAulaA', idHorarioAulaA);
    formData.append('idHorarioAulaB', idHorarioAulaB);

    // Realizar la solicitud fetch al archivo PHP
    fetch('../php/HACursosCreateUpdate.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Imprimir la respuesta del servidor en la consola
            // Puedes realizar otras acciones dependiendo de la respuesta del servidor
        })
        .catch(error => {
            console.error('Error al realizar la solicitud:', error);
        });

}


function createHorarioAJAX(idHorarioAulaB, idCursoA) {
    console.log("idCursoA: " + idCursoA);
    console.log("idHorarioAulaB: " + idHorarioAulaB);

    // Crear un objeto FormData para enviar los datos al archivo PHP
    let formData = new FormData();
    formData.append('idCurso', idCursoA);
    formData.append('idHorarioAula', idHorarioAulaB);

    // Realizar la solicitud fetch al archivo PHP
    fetch('../php/HACursosCreate.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Imprimir la respuesta del servidor en la consola
            // Puedes realizar otras acciones dependiendo de la respuesta del servidor
        })
        .catch(error => {
            console.error('Error al realizar la solicitud:', error);
        });
}

function updateHorariosAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA, idCursoB) {
    console.log("idCursoA: " + idCursoA);
    console.log("idCursoB: " + idCursoB);
    console.log("idHorarioAulaA: " + idHorarioAulaA);
    console.log("idHorarioAulaB: " + idHorarioAulaB);

    // Crear un objeto FormData para enviar los datos al archivo PHP
    let formData = new FormData();
    formData.append('idCursoA', idCursoA);
    formData.append('idCursoB', idCursoB);
    formData.append('idHorarioAulaA', idHorarioAulaA);
    formData.append('idHorarioAulaB', idHorarioAulaB);

    // Realizar la solicitud fetch al archivo PHP
    fetch('../php/HACursosUpdate.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Imprimir la respuesta del servidor en la consola
            // Puedes realizar otras acciones dependiendo de la respuesta del servidor
        })
        .catch(error => {
            console.error('Error al realizar la solicitud:', error);
        });
}

function deleteHorarioAJAX(idHorarioAulaA, idCursoA) {
    console.log("idCursoA: " + idCursoA);
    console.log("idHorarioAulaA: " + idHorarioAulaA);

    // Crear un objeto FormData para enviar los datos al archivo PHP
    let formData = new FormData();
    formData.append('idCurso', idCursoA);
    formData.append('idHorarioAula', idHorarioAulaA);

    // Realizar la solicitud fetch al archivo PHP
    fetch('../php/HACursosDelete.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Imprimir la respuesta del servidor en la consola
            // Puedes realizar otras acciones dependiendo de la respuesta del servidor
        })
        .catch(error => {
            console.error('Error al realizar la solicitud:', error);
        });
}