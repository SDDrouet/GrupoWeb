function createHorario(horario, curso) {
    horario.appendChild(curso);
}

function updateHorarios(horarioA, horarioB, cursoA, cursoB) {
    horarioB.removeChild(cursoB);
    horarioA.appendChild(cursoB);
    horarioA.removeChild(cursoA);
    horarioB.appendChild(cursoA);
}

function deleteHorario(horario, curso) {
    horario.removeChild(curso);
}