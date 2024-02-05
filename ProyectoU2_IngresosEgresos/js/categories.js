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
                                <a href="#" class="btn btn-primary btn-circle btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="#" class="btn btn-danger btn-circle btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>`;
        }

        tableBody_categoria.innerHTML = content;

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