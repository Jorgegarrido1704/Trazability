<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
    <style>
        #continuar {
            transition: color 0.3s ease, transform 0.3s ease;
        }

        #continuar:hover {
            color: red;
            transform: scale(1.1);
        }

        #contList {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        #contList.show {
            opacity: 1;
        }
        #vista {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        #vista.show {
            opacity: 1;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: transform 0.3s ease;
        }

        select:focus {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <nav>
        <div class="container">
            <h1>Registros</h1>
        </div>
        <div class="container">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" href="../Registros/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../nuevalista/nuevalista.php">Crear nueva lista</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="continuar">Continuar lista</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lista.php" id="modificar">Modificar lista</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../vizual/index.php">Ver lista de corte</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" id="contList">
        <!-- Select will be injected here -->
    </div>

    <script>
        const continuar = document.getElementById('continuar');
        // Hover effects for continuar button
        continuar.addEventListener('mouseover', () => {
            continuar.style.color = 'red';
            continuar.style.transform = 'scale(1.1)';
        });
        continuar.addEventListener('mouseout', () => {
            continuar.style.color = 'blue';
            continuar.style.transform = 'scale(1)';
        });
        continuar.addEventListener('click', () => {
    const html = document.getElementById("contList");
    // Show the container smoothly
    html.classList.add('show');
    // Use axios to fetch the data
    axios.get('../app/lista.php')
        .then(function (response) {
            const corte = response.data; 
            console.log(corte);
            let selectHTML = '<form id="cont-lista" method="GET" action="../normal/listaNew.php">';
            selectHTML += '<select id="corteSelect" name="corte" class="form-control">';
            selectHTML += '<option value="" disabled selected>Seleccionar</option>';

            // Generate options dynamically from the response
            corte.forEach(function (corteItem) {
                // Create the option value as a query string
                const optionValue = `${corteItem.creador};${corteItem.cliente};${corteItem.pn};${corteItem.rev}`;
                selectHTML += `<option value="${optionValue}">${corteItem.creador} - ${corteItem.cliente} - ${corteItem.pn} - ${corteItem.rev}</option>`;
            });

            selectHTML += '</select><br><br>';
            selectHTML += '<select id="tipos" name="tipos" class="form-control">';
            selectHTML += '<option value="" disabled selected>Seleccionar</option>';
            selectHTML += '<option value="normal">normal</option>';
            selectHTML += '<option value="especial">especial</option>';
            selectHTML += '</select><br><br>';
            selectHTML += '<div class="input-group mb-3"><button type="submit" class="btn btn-primary">Continuar</button></div><form>';

            html.innerHTML = selectHTML;
            setTimeout(() => {
                document.getElementById('corteSelect').focus();
            }, 500);
        })
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });

    // Prevent the page from refreshing and keep the current URL
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});


    </script>
</body>
</html>
