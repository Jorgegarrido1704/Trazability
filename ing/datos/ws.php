<?php
require './filtros.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <title>Engineering work scheduled</title>
</head>

<body>
    <div class="container">

        <div class="col-12 text-center  mt-5 mb-5">
            <h1>Engineering work scheduled </h1>
            <input type="text" class="form-control" id="pns"  placeholder="Search for PNs.." onchange="search()">
        </div>
        <div class="col-12 text-center mb-5">
            <h2>Buscar por Filtros</h2>
            <div class="row">
                <div class="col-2">
                    <label for="customer">Customer</label>
                    <select name="customer" id="customer" class="form-select" >
                        <option value="">Customer</option>
                        <?php
                        foreach ($datosResp as $key => $value) {
                            echo "<option value='$key'>$value</option>";    
                        }   
                        ?>
                    </select>
                </div>
                <div class="col-1">
                    <label for="size"> Size</label>
                    <select name="size" class="form-select" id="size">
                        <option value="">Size</option>
                        <option value="Ch">Ch</option>
                        <option value="M">M</option>
                        <option value="G">G</option>
                    </select>
                </div>
                <div class="col-2">
                    <label for="responsable">Responsable</label>                    
                    <select name="responsable" class="form-select" id="responsable">
                        <option value="">Responsable</option>
                        <?php
                        foreach ($responsable as $key => $value) {
                            echo "<option value='$key'>$value</option>";    
                        }
                        ?>
                    </select>
                </div>
                <div class="col-1">
                    <label for="Filter">Filter Dates</label>
                    <select name="Filter" class="form-select" id="Filter">
                        <option value="">Filter</option>
                        <option value="MRP">MRP</option>
                        <option value="receiptDate">Receipt Date</option>
                        <option value="commitmentDate">Commitment Date</option>
                        <option value="CompletionDate">Completion Date</option>
                        <option value="customerDate">Customer Date</option>
                    </select>    
                </div>
                <div class="col-2">
                        <label for="DateIni">Date Init:</label><input type="date" class="form-control" id="DateIni"></label>
                </div>
                <div class="col-2">
                        <label for="DateEnd">Date End:</label><input type="date" class="form-control" id="DateEnd"></label>
                </div>
                <div class="col-2">
                    
                    <button class="btn btn-primary" onclick="search()">Search</button>
                </div>
            </div>
        </div>
        <div class="table">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>PN</th>
                        <th>Customer</th>
                        <th>WorkRev</th>
                        <th>Size</th>
                        <th>FullSize</th>
                        <th>MRP</th>
                        <th>Receipt Date</th>
                        <th>Commitment Date</th>
                        <th>Completion Date</th>
                        <th>Documents Approved</th>
                        <th>Status</th>
                        <th>Responsible</th>
                        <th>Customer Date</th>
                        <th>Comments</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody id="table-body">

                   

                </tbody>
            </table>
        </div>
    </div>

</body>

</html><script>
function search() {
    const pns = document.getElementById('pns').value;
    const customer = document.getElementById('customer').value;
    const size = document.getElementById('size').value;
    const responsable = document.getElementById('responsable').value;
    const filter = document.getElementById('Filter').value;
    const dateIni = document.getElementById('DateIni').value;
    const dateEnd = document.getElementById('DateEnd').value;

    let bodyData = {};
    if (pns !== '') {
        bodyData = { pns: pns };
    } else {
        bodyData = {
            customer: customer,
            size: size,
            responsable: responsable,
            filter: filter,
            dateIni: dateIni,
            dateEnd: dateEnd
        };
    }

    fetch('../datos/data.php', {
        method: 'POST',
        body: JSON.stringify(bodyData),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => renderTable(data))
    .catch(error => {
        console.error('Error:', error);
    });
}

function renderTable(data) {
    const tableBody = document.getElementById('table-body');
    tableBody.innerHTML = ''; // Clear previous results

    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.pn}</td>
            <td>${item.customer}</td>
            <td>${item.WorkRev}</td>
            <td>${item.size}</td>
            <td>${item.FullSize}</td>
            <td>${item.MRP}</td>
            <td>${item.receiptDate}</td>
            <td>${item.commitmentDate}</td>
            <td>${item.CompletionDate}</td>
            <td>${item.documentsApproved}</td>
            <td>${item.Status}</td>
            <td>${item.resposible}</td>
            <td>${item.customerDate}</td>
            <td>${item.comments}</td>
            <td><button class="btn btn-success">Edit</button></td>
            <td><button class="btn btn-danger">Delete</button></td>
        `;
        tableBody.appendChild(row);
    });
}
</script>
