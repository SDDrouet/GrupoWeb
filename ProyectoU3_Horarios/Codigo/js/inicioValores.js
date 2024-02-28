const calcularValores = async () => {
    var totalIngresos = 0;
    var totalEgresos = 0;
    var total = 0;

    try {
        const responseIngresos = await fetch("../temp/ingresos.json?123");
        const ingresos = await responseIngresos.json();

        ingresos.forEach(element => {
            totalIngresos += Number(element["valorIngreso"]);
        });

    } catch (ex) {

    }

    try {
        const responseEgresos= await fetch("../temp/egresos.json?123");
        const egresos = await responseEgresos.json();

        egresos.forEach(element => {
            totalEgresos += Number(element["valorEgreso"]);
        });

    } catch (ex) {

    }

    total = totalIngresos - totalEgresos;

    numTotal.innerHTML = "$" + total.toFixed(2).toString();
    numEgresos.innerHTML = "$" + totalEgresos.toFixed(2).toString();
    numIngresos.innerHTML = "$" + totalIngresos.toFixed(2).toString();
};

window.addEventListener("load", async () => {
    await calcularValores();
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

function extraerDatos(datos) {
    datos.forEach(element => {
        datosCargados.push(element);
    });
}

function operacionesDatos(datos) {
    // Operaciones con los datos después de cargar el JSON
    var nombre = datos[0]["nombre"];		
    document.getElementById("usuarioNombre").textContent = nombre;
    document.getElementById("userName2").textContent = nombre;
    
}

// Llamada a la función para cargar datos desde el archivo JSON
cargarDatosDesdeJSON('../temp/usuario.json?123', operacionesDatos)
