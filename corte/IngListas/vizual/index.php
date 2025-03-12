<?php
 require "../Registros/index.php";
    
    
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario para Registrar Datos</title>
    <!-- Link to Bootstrap CSS (from a CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            width: 100%;
        }
        .input-row {
            margin-bottom: 20px;
        }
        #regTable{
            width: 100%;
        }
        table{
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Formulario para Registrar Datos</h2>
            </div>    
        </div>
        <div class="row">
            <div class="col-md-12">  
                <input class="form-control" type="text" name="listas" id="listas" oninput="this.value">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container" id="datosLista">
                <div class="container" id="visual">
                </div>
            </div>
        </div>

    </body>
    </html>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
 
    <script>
        registo=document.getElementById('listas').value;
           function registros(registo){
               //alert(registo);
               axios.get('../app/registro.php?registro='+registo)
               .then(response => {
                   //console.log(response.data);
                   const corte = response.data;
                  // console.log(corte);
                   let selectHTML = '';
                   selectHTML += `
                   <br><br>
                   <table class="table table-bordered table-striped" id="regTable">
                   <thead>
                       <tr>
                           <th>Creador</th>
                            <th>Cliente</th>
                            <th>Numero de parte</th>
                            <th>Revision</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${corte[0].creadorLista}</td>
                            <td>${corte[0].client}</td>
                            <td>${corte[0].pn}</td>
                            <td>${corte[0].rev}</td>
                        </tr>
                    </tbody>
                </table>    
                   
                   `;


                   selectHTML += `<br><div class="table-responsive">
                   <table class="table table-bordered table-striped" id="regTable">
                       <thead>
                            
                           <tr>
                               <th>Corte</th>
                               <th>Consecutivo</th>
                               
                               <th>Tipo de cable</th>
                               <th>Color</th>
                               <th>Calibre</th>
                                 <th>Longitud</th>
                                 <th>Terminal 1</th>
                                    <th>Terminal 2</th>
                                    <th>Estampado</th>
                                    <th>fromPos</th>
                                    <th>toPos</th>
                                    <th>Comentario</th>
                              </tr>
                              </thead>
                              <tbody>
                              `;
                   corte.forEach(function (corteItem) {
                       selectHTML += `<tr>
                           <td>${corteItem.corte}</td>
                            <td>${corteItem.cons}</td>
                            <td>${corteItem.tipo}</td>
                            <td>${corteItem.color}</td>
                            <td>${corteItem.calibre}</td>
                            <td>${corteItem.longitud}</td>
                            <td>${corteItem.term1}</td>
                            <td>${corteItem.term2}</td>
                            <td>${corteItem.estamp}</td>
                            <td>${corteItem.fromPos}</td>
                            <td>${corteItem.toPos}</td>
                            <td>${corteItem.comment}</td>
                            </tr>`;
                   });
                   selectHTML += `</tbody>
                   </table>
                   </div>`;
                document.getElementById('visual').innerHTML = selectHTML;
               })
                .catch(error => {
                     console.error(error);
                });

           }
           const timeout = 2000; 
           
             
setInterval(() => {
    registo=document.getElementById('listas').value;
    if (registo != "") {
        registros(registo);
    }
   
}, timeout);

    </script>
