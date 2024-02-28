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

    dataTable = $("#datatable_egresos").DataTable(dataTableOptions);

    dataTableIsInitialized = true;
};

const listUsers = async () => {
    try {
        const response = await fetch("../temp/egresos.json");
        const users = await response.json();

        let content = '';

        for (let i = users.length - 1; i >= 0; i--) {
            const user = users[i];
            content += `
            <tr>
                <td>${users.length - i}</td>
                <td>${user.fechaEgreso}</td>
                <td>${user.categoriaEgreso}</td>
                <td>${user.notaEgreso}</td>
                <td>${user.usuarioEgreso}</td>
                <td>${user.valorEgreso}</td>
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

        tableBody_egresos.innerHTML = content;

        // Asigna eventos a los botones de editar y eliminar después de agregarlos al DOM
        document.querySelectorAll('.btn-editM').forEach(button => {
            button.addEventListener('click', handleEdit);
        });

        document.querySelectorAll('.btn-deleteM').forEach(button => {
            button.addEventListener('click', handleDelete);
        });

    } catch (ex) {
        //console.log(ex);
    }
};

const actualizarTabla = async () => {
    await initDataTable();
}

window.addEventListener("load", async () => {
    await initDataTable();
});

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
    var selector = document.getElementById("categoriaEgreso");

    datos.forEach(element => {
        var opcion = document.createElement("option");
        opcion.text = element["nombreCategoria"];
        opcion.value = element["nombreCategoria"]; // Puedes establecer un valor específico si lo deseas
        selector.add(opcion);
    });

}

function agregarCategorias2(datos) {
    var selector = document.getElementsByName("categoriaEgreso")[0];

    datos.forEach(element => {
        var opcion = document.createElement("option");
        opcion.text = element["nombreCategoria"];
        opcion.value = element["nombreCategoria"]; // Puedes establecer un valor específico si lo deseas
        selector.add(opcion);
    });

}

// Llamada a la función para cargar datos desde el archivo JSON
cargarDatosDesdeJSON('../temp/usuario.json', operacionesDatos)
cargarDatosDesdeJSON('../temp/categorias.json', agregarCategorias);
cargarDatosDesdeJSON('../temp/categorias.json', agregarCategorias2);

function eliminarArch() {
    eliminarArchivos();
}

async function enviarFormulario() {
    const formulario = document.getElementById('frmEgresos');
    const datos = {
        fechaEgreso: formulario.fechaEgreso.value,
        categoriaEgreso: formulario.categoriaEgreso.value,
        notaEgreso: formulario.notaEgreso.value,
        usuarioEgreso: formulario.usuarioEgreso.value,
        valorEgreso: formulario.valorEgreso.value
    };
    // Obtener el nombre del archivo externamente
    const nombreArchivo = "../temp/egresos.json"

    // Llamada a la función para enviar datos al servidor
    await enviarDatosAlServidor('../php/guardar_datos.php', datos, nombreArchivo);

    await actualizarTabla();

    formulario.fechaEgreso.value = "";
    formulario.categoriaEgreso.value = "";
    formulario.notaEgreso.value = "";
    formulario.usuarioEgreso.value = "";
    formulario.valorEgreso.value = "";
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
        fechaEgreso: formulario.fechaEgreso.value,
        categoriaEgreso: formulario.categoriaEgreso.value,
        notaEgreso: formulario.notaEgreso.value,
        usuarioEgreso: formulario.usuarioEgreso.value,
        valorEgreso: formulario.valorEgreso.value
    };

    var valor = Number(datos.valorEgreso);
    if (validarCadena(datos.fechaEgreso) && validarCadena(datos.categoriaEgreso) && validarCadena(datos.notaEgreso)
        && validarCadena(datos.usuarioEgreso) && valor > 0) {
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
    addEvento("frmEgresos", "btnFrmEgresos", "keyup");
    addEvento("frmEgresosEditor", "btnFrmEgresosEditor", "keyup");
    addEvento("frmEgresos", "btnFrmEgresos", "change");
    addEvento("frmEgresosEditor", "btnFrmEgresosEditor", "change");
});

var indiceActual = 0;
var indiceEliminar = 0;
const handleEdit = async (event) => {
    const index = event.target.dataset.index;
    indiceActual = index;
};

function limpiarCamposEditor() {
    const formulario = document.getElementById("frmEgresosEditor");
    formulario.fechaEgreso.value = "";
    formulario.categoriaEgreso.value = "";
    formulario.notaEgreso.value = "";
    formulario.usuarioEgreso.value = "";
    formulario.valorEgreso.value = "";

}

async function enviarFormularioEditor() {
    const formulario = document.getElementById("frmEgresosEditor");
    const datosActualizados = {
        fechaEgreso: formulario.fechaEgreso.value,
        categoriaEgreso: formulario.categoriaEgreso.value,
        notaEgreso: formulario.notaEgreso.value,
        usuarioEgreso: formulario.usuarioEgreso.value,
        valorEgreso: formulario.valorEgreso.value
    };

    limpiarCamposEditor();
    // Obtener el nombre del archivo externamente
    const nombreArchivo = "../temp/egresos.json";

    await editarRegistro(nombreArchivo, indiceActual, datosActualizados);
    await actualizarTabla();
}

async function eliminarRegistrofun() {
    await eliminarRegistro("../temp/egresos.json", indiceEliminar);
    await actualizarTabla();
}

// Función para manejar el clic en el botón de eliminar
const handleDelete = async (event) => {
    const index = event.target.dataset.index;
    indiceEliminar = index;
};
