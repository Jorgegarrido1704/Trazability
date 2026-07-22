function buscarDatos(pn){
    const url = `process/buscarProcess.php?pn=${pn}`;
    
    fetch(url)
    .then(response => response.json())
    .then(data => {
      //  console.log(data);
       if(data.error == "No se encontraron datos"){
            var tabla = document.getElementById("registrosPrevios");
            tabla.innerHTML = "";
       }
           
       
        var tabla = document.getElementById("registrosPrevios");
        tabla.innerHTML = "";
        data.data.forEach(element => {
            var row = document.createElement("tr");
            var cell1 = document.createElement("td");
            cell1.textContent = element.pn_routing;
            var cell2 = document.createElement("td");
            cell2.textContent = element.routingNumber;
            var cell3 = document.createElement("td");
            cell3.textContent = element.routingDescription;
            var cell7 = document.createElement("td");
            cell7.textContent = element.posibleAssets;
            var cell4 = document.createElement("td");
            cell4.textContent = element.QtyTimes;
            var cell5 = document.createElement("td");
            cell5.textContent = element.timePerProcess;
            var cell6 = document.createElement("td");
            cell6.textContent = element.setUp_routing;
            row.appendChild(cell1);
            row.appendChild(cell2);
            row.appendChild(cell3);
            row.appendChild(cell7);
            row.appendChild(cell4);
            row.appendChild(cell5);
            row.appendChild(cell6);
            tabla.appendChild(row);
        });
   
        
    });
   
}
function posibles(){
    const url = `process/process.php`;
    fetch(url)
    .then(response => response.json())
    .then(data => {
        var select_process = document.getElementById("descripcionRuteo");
        data.forEach(element => {
            var option = document.createElement("option");
            option.value = element.routingDescription;
            option.textContent = element.routingDescription;
            select_process.appendChild(option);
        });
    });

}


function agregar(){
    var pn_routing = document.getElementById("routingNumber").value;
    var work_description = document.getElementById("descripcionRuteo").value;
    var posible_stations = document.getElementById("posiblesAssets").value;
    var routing_number = document.getElementById("routing_number").value;
    var QtyTimes = document.getElementById("qtyTimes").value;
    var timePerProcess = document.getElementById("timeProcess").value;
   // console.log(pn_routing,work_description,posible_stations,routing_number,QtyTimes,timePerProcess);
    const url = `process/addProcess.php?routingNumber=${pn_routing}&descripcionRuteo=${work_description}&posiblesAssets=${posible_stations}&qtyTimes=${QtyTimes}&timeProcess=${timePerProcess}&routing_number=${routing_number}`;
    try{
    fetch(url)
    .then(response => response.json())
    .then(data => {
       // console.log(data);
        
    });
}catch(e){
    //console.log(e);
}
buscarDatos(pn_routing);
}

function buscarComplementos(routingDescription){
    const url = `process/routing_complements.php?routingDescription=${routingDescription}`;
    fetch(url)
    .then(response => response.json())
    .then(data => {
     //   console.log(data);
        var select_asset = document.getElementById("posiblesAssets");
        select_asset.innerHTML = '  <option value="" disabled selected> Seleccionar</option> ';
         var select_num_routing = document.getElementById("routing_number");
        select_num_routing.innerHTML = '  <option value="" disabled selected> Seleccionar</option>';
        data.forEach(element => {
            var option = document.createElement("option");
            var option2 = document.createElement("option");
            option.value = element.posibleAssets;
            option.textContent = element.posibleAssets;
            select_asset.appendChild(option);
            option2.value = element.routingNumber;
            option2.textContent = element.routingNumber;
            select_num_routing.appendChild(option2);
        });
        
    });
}


window.onload = function() {
    posibles();
}