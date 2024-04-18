function addPerformance() {
    const perfDiv = document.getElementById("perfomanceTableBody");
    const perfObject = document.getElementById("selectPerf").value;
    if (perfObject !== "Ajouter une prestation") {
        const perfArray = perfObject.split('!');
        let quantity = perfArray[1];
        let designation = perfArray[2];
        let price = perfArray[3];
        let unit = perfArray[4];
        console.log(perfArray);
        const test = document.createElement("tr");
        test.setAttribute("value", perfArray[0])
        test.innerHTML = "<td>" + quantity + "</td><td>" + designation + "</td><td>" + price + "</td><td>" + unit + "</td>";
        perfDiv.append(test);
    }
}

function removeLast() {
    var select = document.getElementById('perfomanceTableBody');
    select.removeChild(select.lastChild);
}
// {
//     "indexArray" : 1,
//     "quantity" : 32,
//     "unit" : Kilo,
//     "designation" : Pigeon,
//     "price" : 146.32
// }