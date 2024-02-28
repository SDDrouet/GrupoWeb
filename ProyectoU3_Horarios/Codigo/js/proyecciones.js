
const calcularProyecciones = async () => {
    try {

        const responseIngresos = await fetch("../temp/ingresos.json?123");
        const ingresos = await responseIngresos.json();
        const responseEgresos= await fetch("../temp/egresos.json?123");
        const egresos = await responseEgresos.json();

        const datosCombinados = [];

        // Determinar la longitud máxima entre ingresos y egresos
        const max = Math.max(ingresos.length, egresos.length);
        const min = Math.min(ingresos.length, egresos.length);
        
        for (let i = 0; i < min; i++) {
            const ingreso = ingresos[i];
            const egreso = egresos[i];

            datosCombinados.push({ fechaEgreso: egreso.fechaEgreso, valorEgreso: egreso.valorEgreso,
                                    fechaIngreso: ingreso.fechaIngreso, valorIngreso: ingreso.valorIngreso});
        }
        
        for (let i = min; i < max; i++) {
            const ingreso = ingresos[i];
            const egreso = egresos[i];
            if (ingresos.length > egresos.length) {
                datosCombinados.push({ fechaEgreso: "", valorEgreso: "",
                    fechaIngreso: ingreso.fechaIngreso, valorIngreso: ingreso.valorIngreso});
            } else {
                datosCombinados.push({ fechaEgreso: egreso.fechaEgreso, valorEgreso: egreso.valorEgreso,
                    fechaIngreso: "", valorIngreso: ""});
            }
        }      
        
        await enviarDatosAlServidorConjunto('../php/guardar_proyecciones.php', datosCombinados);
        
         

    } catch (ex) {
        console.log(ex);
    }
};

window.addEventListener("load", async () => {
    await calcularProyecciones();
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

window.addEventListener("load", async () => {
    await eliminarArchivos();
});