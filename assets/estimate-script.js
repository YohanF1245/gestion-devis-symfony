
function addPerformance() {
    const perfDiv = document.getElementById("perfomanceTableBody");
    let i = perfDiv.childElementCount +1;
    const perfObject = document.getElementById("selectPerf").value;
    if (perfObject !== "Ajouter une prestation") {
        const perfArray = perfObject.split('!');
        console.log(perfArray);
        let quantity = perfArray[1];
        let unit = perfArray[2];
        let designation = perfArray[3];
        let price = perfArray[4];
        let id = perfArray[5];
        let tax = perfArray[6];
        console.log(perfArray);
        const test = document.createElement("tr");
        test.setAttribute("value", perfArray[0])
        test.innerHTML = "<input type='hidden' name='perfNum"+i+"' value='"+id+"'/>";
        test.innerHTML += "<td><input type='text' value='" + quantity+"' readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + designation+"' readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + unit+"' readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + price+" €' readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + tax+" %"+ "'readonly style='border:none!important'/></td>";
        test.innerHTML += "<td><input type='text' value='" + quantity*price+" €' readonly style='border:none!important'/></td>";
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