let i = 1;
function addPerformance() {
    const perfDiv = document.getElementById("perfomanceTableBody");
    const perfObject = document.getElementById("selectPerf").value;
    if (perfObject !== "Ajouter une prestation") {
        const perfArray = perfObject.split('!');
        let quantity = perfArray[1];
        let designation = perfArray[2];
        let price = perfArray[3];
        let unit = perfArray[4];
        let id = perfArray[5];
        console.log(perfArray);
        const test = document.createElement("tr");
        test.setAttribute("value", perfArray[0])
        test.innerHTML = "<input type='hidden' name='perfNum"+i+"' value='"+id+"'/>";
        test.innerHTML += "<td><input type='text' value='" + quantity+"' readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + designation+"' readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + price+"' readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + unit+"' readonly style='border:none!important'/></td>";
        //  + "</td><td>" + designation + "</td><td>" + price + "</td><td>" + unit + "</td>";
        perfDiv.append(test);
        document.getElementById("prestationTotal").value = i;
        i++;
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