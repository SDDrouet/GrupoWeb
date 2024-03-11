function createHorario(horarioA, horarioB, cursoA) {
    let idCursoA = cursoA.querySelector('.IDOculto').textContent;
    let idHorarioAulaA = horarioA.id;
    let idHorarioAulaB = horarioB.id;
    idHorarioAulaA = idHorarioAulaA.replace("id_hr", "");
    idHorarioAulaB = idHorarioAulaB.replace("id_hr", "");
    
    createHorarioAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA);
    horarioB.appendChild(cursoA);

}

function updateHorarios(horarioA, horarioB, cursoA, cursoB) {
    let idCursoA = cursoA.querySelector('.IDOculto').textContent;
    let idCursoB = cursoB.querySelector('.IDOculto').textContent;
    let idHorarioAulaA = horarioA.id;
    let idHorarioAulaB = horarioB.id;
    idHorarioAulaA = idHorarioAulaA.replace("id_hr", "");
    idHorarioAulaB = idHorarioAulaB.replace("id_hr", "");

    if (idCursoA !== idCursoB) {
        updateHorariosAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA, idCursoB);

        horarioB.removeChild(cursoB);
        horarioA.appendChild(cursoB);
        horarioA.removeChild(cursoA);
        horarioB.appendChild(cursoA);
    }

}

function deleteHorario(horarioA, cursoA) {
    let idCursoA = cursoA.querySelector('.IDOculto').textContent;
    let idHorarioAulaA = horarioA.id;
    idHorarioAulaA = idHorarioAulaA.replace("id_hr", "");

    deleteHorarioAJAX(idHorarioAulaA, idCursoA);
    horarioA.removeChild(cursoA);
}


function createHorarioAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA) {
    console.log("idCursoA: " + idCursoA);
    console.log("idHorarioAulaA: " + idHorarioAulaA);
    console.log("idHorarioAulaB: " + idHorarioAulaB);
}

function updateHorariosAJAX(idHorarioAulaA, idHorarioAulaB, idCursoA, idCursoB) {
    console.log("idCursoA: " + idCursoA);
    console.log("idCursoB: " + idCursoB);
    console.log("idHorarioAulaA: " + idHorarioAulaA);
    console.log("idHorarioAulaB: " + idHorarioAulaB);
}

function deleteHorarioAJAX(idHorarioAulaA, idCursoA) {
    console.log("idCursoA: " + idCursoA);
    console.log("idHorarioAulaA: " + idHorarioAulaA);
}