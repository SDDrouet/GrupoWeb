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