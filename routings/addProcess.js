function buscarDatos(pn){
    const url = `buscarProcess.php?pn=${pn}`;
    fetch(url)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        var tabla = document.getElementById("registrosPrevios");
        tabla.innerHTML = "";
        data.forEach(element => {
            var row = document.createElement("tr");
            var cell1 = document.createElement("td");
            cell1.textContent = element.pn_routing;
            var cell2 = document.createElement("td");
            cell2.textContent = element.work_description;
            var cell3 = document.createElement("td");
            cell3.textContent = element.posible_stations;
            var cell4 = document.createElement("td");
            cell4.textContent = element.QtyTimes;
            var cell5 = document.createElement("td");
            cell5.textContent = element.timePerProcess;
            row.appendChild(cell1);
            row.appendChild(cell2);
            row.appendChild(cell3);
            row.appendChild(cell4);
            row.appendChild(cell5);
            tabla.appendChild(row);
        });
    });
}
function posible(){

}