datatable_categoria

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

    dataTable = $("#datatable_categoria").DataTable(dataTableOptions);

    dataTableIsInitialized = true;
};

const listUsers = async () => {
    try {
        const response = await fetch("../temp/categorias.json");
        const users = await response.json();

        let content = '';
        for (let i = users.length - 1; i >= 0; i--) {
            const user = users[i];
            content += `
                        <tr>
                            <td>${users.length - i}</td>
                            <td>${user.nombreCategoria}</td>
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

        tableBody_categoria.innerHTML = content;

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
                .then((response) => {
                    // Verificar si la respuesta es exitosa (código de estado 200)
                    if (!response.ok) {
                        throw new Error(
                            `Error al cargar el archivo JSON. Código de estado: ${response.status}`
                        );
                    }
                    // Devolver el cuerpo de la respuesta como JSON
                    return response.json();
                })
                .then((datos) => {
                    funcion(datos);
                })
                .catch((error) => {
                    console.error("Error:", error.message);
                });
        }

        function extraerDatos(datos) {
            datos.forEach((element) => {
                datosCargados.push(element);
            });
        }

        function operacionesDatos(datos) {
            var nombre = datos[0]["nombre"];
            document.getElementById("usuarioNombre").textContent = nombre;
            document.getElementById("userName2").textContent = nombre;
        }

        // Llamada a la función para cargar datos desde el archivo JSON
        cargarDatosDesdeJSON("../temp/usuario.json", operacionesDatos);

        async function enviarFormulario() {
            const formulario = document.getElementById("frmCategorias");
            const datos = {
                nombreCategoria: formulario.nombreCategoria.value,
            };

            formulario.nombreCategoria.value = "";
            // Obtener el nombre del archivo externamente
            const nombreArchivo = "../temp/categorias.json";
            document.getElementById("btnFrmCategorias").disabled = true;
            // Llamada a la función para enviar datos al servidor
            await enviarDatosAlServidor(
                "../php/guardar_datos.php",
                datos,
                nombreArchivo
            );

            await actualizarTabla();
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
            const datos = formulario.nombreCategoria.value;

            return validarCadena(datos);
        }

        function addEvento(idFrm, idFrmBtn, eventoName) {
            var formulario = document.getElementById(idFrm);

            // Agregar un evento 'change' a todos los elementos dentro del formulario
            formulario.addEventListener(eventoName, function (event) {
                // Obtener el elemento que desencadenó el evento

                var btn = document.getElementById(idFrmBtn);

                if (esValido(idFrm)) {
                    btn.disabled = false;
                } else {
                    btn.disabled = true;
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            addEvento("frmCategorias", "btnFrmCategorias", "keyup");
            addEvento("frmCategoriasEditor", "btnFrmCategoriasEditor", "keyup");
            addEvento("frmCategorias", "btnFrmCategorias", "change");
            addEvento("frmCategoriasEditor", "btnFrmCategoriasEditor", "change");
        });

        document
            .getElementById("frmCategorias")
            .addEventListener("keydown", function (e) {
                // Verificar si la tecla presionada es Enter
                if (e.key === "Enter") {
                    e.preventDefault(); // Evitar el comportamiento predeterminado del Enter (enviar formulario)

                    // Verificar si todos los campos están llenos
                    if (esValido()) {
                        enviarFormulario();
                    } else {
                        
                    }
                }
            });

        var indiceActual = 0;
        var indiceEliminar = 0;
        const handleEdit = async (event) => {
            const index = event.target.dataset.index;
            indiceActual = index;
        };

        function limpiarCamposEditor() {
            const formulario = document.getElementById("frmCategoriasEditor");
            formulario.nombreCategoria.value = "";
        }

        async function enviarFormularioEditor() {
            const formulario = document.getElementById("frmCategoriasEditor");
            const datosActualizados = {
                nombreCategoria: formulario.nombreCategoria.value,
            };

            limpiarCamposEditor();
            // Obtener el nombre del archivo externamente
            const nombreArchivo = "../temp/categorias.json";
            
            await editarRegistro(nombreArchivo, indiceActual, datosActualizados);
            await actualizarTabla();
            document.getElementById("btnFrmCategoriasEditor").disabled = true;
        }

        async function eliminarRegistrofun() {
            await eliminarRegistro("../temp/categorias.json", indiceEliminar);
            await actualizarTabla();
        }
        
        // Función para manejar el clic en el botón de eliminar
        const handleDelete = async (event) => {
            const index = event.target.dataset.index;
            indiceEliminar = index;
        };
   