
const calcularValores = async () => {
    try {
        const responseIngresos = await fetch("../temp/ingresos.json?123");
        const ingresos = await responseIngresos.json();
        const responseEgresos= await fetch("../temp/egresos.json?123");
        const egresos = await responseEgresos.json();

        var totalIngresos = 0;
        var totalEgresos = 0;
        var total = 0;

        console.log(ingresos);
        console.log(egresos);
        
        ingresos.forEach(element => {
            totalIngresos += Number(element["valorIngreso"]);
        });

        egresos.forEach(element => {
            totalEgresos += Number(element["valorEgreso"]);
        });

        total = totalIngresos - totalEgresos;

        numTotal.innerHTML = "$" + total.toFixed(2).toString();
        numEgresos.innerHTML = "$" + totalEgresos.toFixed(2).toString();
        numIngresos.innerHTML = "$" + totalIngresos.toFixed(2).toString();

    } catch (ex) {
        console.log(ex);

        numTotal.innerHTML = "$0.00";
        numEgresos.innerHTML = "$0.00";
        numIngresos.innerHTML = "$0.00";
    }
};

window.addEventListener("load", async () => {
    await calcularValores();
});