let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    destroy: true,
    language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "Ningún usuario encontrado",
        info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
        infoEmpty: "No hay registros disponibles",
        infoFiltered: "(filtrados desde _MAX_ registros totales)",
        search: "Buscar:",
        loadingRecords: "Cargando...",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    }
};

const initDataTable = async () => {
    if (dataTableIsInitialized) {
        dataTable.destroy();
    }

    await listUsers();

    dataTable = $("#datatable_ingresos").DataTable(dataTableOptions);

    dataTableIsInitialized = true;
};

const listUsers = async () => {
    try {
        const response = await fetch("../temp/ingresos.json");
        const users = await response.json();

        let content = '';
        for (let i = users.length - 1; i >= 0; i--) {
            const user = users[i];
            content += `
            <tr>
                <td>${users.length - i}</td>
                <td>${user.fechaIngreso}</td>
                <td>${user.categoriaIngreso}</td>
                <td>${user.notaIngreso}</td>
                <td>${user.usuarioIngreso}</td>
                <td>${user.valorIngreso}</td>
                <td>
                    <a href="#" class="btn btn-primary btn-circle btn-sm btn-editM" data-toggle="modal" data-target="#exampleModalCenterEditor" data-index="${i}">
                        <i class="fas fa-edit" data-index="${i}"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-circle btn-sm btn-deleteM" data-toggle="modal" data-target="#confirmarEliminar" data-index="${i}">
                        <i class="fas fa-trash" data-index="${i}"></i>
                    </a>
                </td>
            </tr>`;
        }

        tableBody_ingresos.innerHTML = content;

        // Asigna eventos a los botones de editar y eliminar después de agregarlos al DOM
        document.querySelectorAll('.btn-editM').forEach(button => {
            button.addEventListener('click', handleEdit);
        });

        document.querySelectorAll('.btn-deleteM').forEach(button => {
            button.addEventListener('click', handleDelete);
        });

    } catch (ex) {
        console.log(ex);
    }
};

const actualizarTabla = async () => {
    await initDataTable();
}

window.addEventListener("load", async () => {
    await initDataTable();
});


// script.js

function cargarDatosDesdeJSON(rutaArchivo, funcion) {
    // Retornar la promesa generada por fetch para su uso externo
    return fetch(rutaArchivo)
        .then(response => {
            // Verificar si la respuesta es exitosa (código de estado 200)
            if (!response.ok) {
                throw new Error(`Error al cargar el archivo JSON. Código de estado: ${response.status}`);
            }
            // Devolver el cuerpo de la respuesta como JSON
            return response.json();
        })
        .then(datos => {

            funcion(datos)

        })
        .catch(error => {
            console.error('Error:', error.message);
        });
}

function operacionesDatos(datos) {
    var nombre = datos[0]["nombre"];
    document.getElementById("usuarioNombre").textContent = nombre;
    document.getElementById("userName2").textContent = nombre;
}

function agregarCategorias(datos) {
    var selector = document.getElementById("categoriaIngreso");

    datos.forEach(element => {
        var opcion = document.createElement("option");
        opcion.text = element["nombreCategoria"];
        opcion.value = element["nombreCategoria"]; // Puedes establecer un valor específico si lo deseas
        selector.add(opcion);
    });

}

function agregarCategorias2(datos) {
    var selector = document.getElementsByName("categoriaIngreso")[0];

    datos.forEach(element => {
        var opcion = document.createElement("option");
        opcion.text = element["nombreCategoria"];
        opcion.value = element["nombreCategoria"]; // Puedes establecer un valor específico si lo deseas
        selector.add(opcion);
    });

}

// Llamada a la función para cargar datos desde el archivo JSON
cargarDatosDesdeJSON('../temp/usuario.json', operacionesDatos);
cargarDatosDesdeJSON('../temp/categorias.json', agregarCategorias);
cargarDatosDesdeJSON('../temp/categorias.json', agregarCategorias2);



function eliminarArch() {
    eliminarArchivos();
}

async function enviarFormulario() {
    const formulario = document.getElementById('frmIngresos');
    const datos = {
        fechaIngreso: formulario.fechaIngreso.value,
        categoriaIngreso: formulario.categoriaIngreso.value,
        notaIngreso: formulario.notaIngreso.value,
        usuarioIngreso: formulario.usuarioIngreso.value,
        valorIngreso: formulario.valorIngreso.value
    };
    // Obtener el nombre del archivo externamente
    const nombreArchivo = "../temp/ingresos.json"

    // Llamada a la función para enviar datos al servidor
    await enviarDatosAlServidor('../php/guardar_datos.php', datos, nombreArchivo);

    await actualizarTabla();

    formulario.fechaIngreso.value = "";
    formulario.categoriaIngreso.value = "";
    formulario.notaIngreso.value = "";
    formulario.usuarioIngreso.value = "";
    formulario.valorIngreso.value = "";
}


function validarCadena(cadena) {
    // Verificar si la cadena es nula, indefinida o vacía
    if (!cadena || cadena.trim() === '') {
        return false; // La cadena es nula, indefinida o vacía
    } else {
        return true; // La cadena no es nula, indefinida ni vacía
    }
}

function esValido(idFrm) {
    var formulario = document.getElementById(idFrm);

    const datos = {
        fechaIngreso: formulario.fechaIngreso.value,
        categoriaIngreso: formulario.categoriaIngreso.value,
        notaIngreso: formulario.notaIngreso.value,
        usuarioIngreso: formulario.usuarioIngreso.value,
        valorIngreso: formulario.valorIngreso.value
    };

    var valor = Number(datos.valorIngreso);
    if (validarCadena(datos.fechaIngreso) && validarCadena(datos.categoriaIngreso) && validarCadena(datos.notaIngreso)
        && validarCadena(datos.usuarioIngreso) && valor > 0) {
        return false;
    } else {
        return true;
    }

}

function addEvento(idFrm, idFrmBtn, eventoName) {
    var formulario = document.getElementById(idFrm);

    // Agregar un evento 'change' a todos los elementos dentro del formulario
    formulario.addEventListener(eventoName, function (event) {
        // Obtener el elemento que desencadenó el evento

        var btn = document.getElementById(idFrmBtn);

        if (esValido(idFrm)) {
            btn.disabled = true;
        } else {
            btn.disabled = false;
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    addEvento("frmIngresos", "btnFrmIngresos", "keyup");
    addEvento("frmIngresosEditor", "btnFrmIngresosEditor", "keyup");
    addEvento("frmIngresos", "btnFrmIngresos", "change");
    addEvento("frmIngresosEditor", "btnFrmIngresosEditor", "change");
});



var indiceActual = 0;
var indiceEliminar = 0;
const handleEdit = async (event) => {
    const index = event.target.dataset.index;
    indiceActual = index;
};

function limpiarCamposEditor() {
    const formulario = document.getElementById("frmIngresosEditor");
    formulario.fechaIngreso.value = "";
    formulario.categoriaIngreso.value = "";
    formulario.notaIngreso.value = "";
    formulario.usuarioIngreso.value = "";
    formulario.valorIngreso.value = "";

}

async function enviarFormularioEditor() {
    const formulario = document.getElementById("frmIngresosEditor");
    const datosActualizados = {
        fechaIngreso: formulario.fechaIngreso.value,
        categoriaIngreso: formulario.categoriaIngreso.value,
        notaIngreso: formulario.notaIngreso.value,
        usuarioIngreso: formulario.usuarioIngreso.value,
        valorIngreso: formulario.valorIngreso.value
    };

    limpiarCamposEditor();
    // Obtener el nombre del archivo externamente
    const nombreArchivo = "../temp/ingresos.json";

    await editarRegistro(nombreArchivo, indiceActual, datosActualizados);
    await actualizarTabla();
}

async function eliminarRegistrofun() {
    await eliminarRegistro("../temp/ingresos.json", indiceEliminar);
    await actualizarTabla();
}

// Función para manejar el clic en el botón de eliminar
const handleDelete = async (event) => {
    const index = event.target.dataset.index;
    indiceEliminar = index;
};
