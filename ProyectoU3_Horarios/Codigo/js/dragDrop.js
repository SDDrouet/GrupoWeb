var trash = document.getElementById("trash");
var horarios = document.querySelectorAll('.horario');
var selected;

function dropHandler(e) {
  e.preventDefault();
  e.stopPropagation();

  let padre = selected.parentNode;

  if (selected) {
    if (this.childElementCount === 0) {
      let horarioA = selected.parentNode;
      createHorario(horarioA, this, selected)
      if (padre.id === "cursosContainer") {
        // Clonar el div
        let clone = selected.cloneNode(true);
        dropManager(clone);
        padre.appendChild(clone);
      }
    } else {
      let horarioA = selected.parentNode;
      let horarioB = this;
      let cursoA = selected;
      let cursoB = this.firstElementChild;
      updateHorarios(horarioA, horarioB, cursoA, cursoB);
    }

    selected = null; // Mover esta línea fuera del bloque if
    removeHorarioDropListener();
  } else {
    removeHorarioDropListener();
  }
}

function deleteHandler(e) {
  e.preventDefault();

  let padre = selected.parentNode;
  if (padre.id !== "cursosContainer") {
    deleteHorario(selected.parentNode, selected);
  }
  selected = null;
  removeHorarioDropListener();
}

function removeHorarioDropListener() {
  trash.removeEventListener("drop", deleteHandler);
}

function removeHorarioDropListener() {
  horario.removeEventListener("drop", dropHandler);
}


function dropManager(curso) {
  curso.setAttribute("draggable", "true");

  curso.addEventListener("dragstart", function (e) {
    selected = e.target;
    e.stopPropagation();

    // Escuchar evento dragover y drop para cada dia
    for (horario of horarios) {
      horario.addEventListener("dragover", function (e) {
        e.preventDefault();
      });

      horario.addEventListener("drop", dropHandler);

    }

    // Escuchar evento dragover y drop para el contenedor de basura
    trash.addEventListener("dragover", function (e) {
      e.preventDefault();
    });

    trash.addEventListener("drop", deleteHandler);


  });
}