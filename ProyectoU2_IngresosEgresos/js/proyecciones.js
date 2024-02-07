
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