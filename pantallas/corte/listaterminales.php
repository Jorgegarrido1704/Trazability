

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materialistas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        /* Configuración de página para etiquetas */
        @page {
            size: 104mm 57.5mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            background-color: white;
        }

        /* Salto de página para cada etiqueta */
        .label-container {
            width: 104mm;
            height: 57.5mm;
            box-sizing: border-box;
            padding: 3mm;
            padding-top: 35px;
            display: flex; /* Dividimos en Izquierda (Barcode) y Derecha (Info) */
            overflow: hidden;
            page-break-after: always;
            border: 0.5mm dashed #eee; /* Solo para visualización, se puede quitar */
        }

        /* Contenedor del código de barras vertical */
        .barcode-side {
            width: 12mm;
            display: flex;
            align-items: center;
            justify-content: center;
           
        }

      /*  .barcode-vertical {
            transform: rotate(-90deg); 
            transform-origin: center;
            border: 1px dashed #000;0
        } 
        */

        
        .info-side {
            flex: 1;
            padding-left: 2mm;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            margin-bottom: 2px;
            padding-bottom: 2px;
        }

        .logo {
            width: 50px;
            height: auto;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr ;
            gap: 1px;
            line-height: 1.1;
        }

        .full-width {
            grid-column: span 2;
        }

        .label-bold {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;

        }

        .footer-barcode {
           
            text-align: center;
        }

        #bcode-canvas {
            max-width: 100%;
            height: 6mm;
        }
   
        /* Corrección para evitar conflictos en vistas Bootstrap */
        .bootstrap-scope img, .bootstrap-scope svg { display: inline; }
       
    </style>
</head>
<body class="bg-light">
<div id="app">

 <div class="container-fluid px-4 tab-content-all" id="materialistas">
            <nav class="navbar navbar-expand bg-secondary mb-4 rounded px-2">
                <input type="hidden" id="maquina_material" value="MCUT-1">
                <div class="navbar-nav me-auto">
                    <span class="navbar-text text-white"><h2>Terminales que se puede aplicar en Maquina <h2></span>
            </nav>
            <div class="row g-4">
                
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 text-secondary">Terminales </h5>
                            <span class="badge bg-primary rounded-pill" id="totalterminales">0</span>
                        </div>
                        <div class="card-body p-0"><table class="table mb-0"><tbody id="terminalspormaquina"></tbody></table></div>
                    </div>
                </div>
                  <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 text-secondary">Terminales </h5>
                            <span class="badge bg-primary rounded-pill" id="totalterminales2">0</span>
                        </div>
                        <div class="card-body p-0"><table class="table mb-0"><tbody id="terminalspormaquina2"></tbody></table></div>
                    </div>
                </div>
               <!-- <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 text-secondary">Herramentales</h5>
                        </div>
                        <div class="card-body p-0"><table class="table mb-0"><tbody id="herramentales"></tbody></table></div>
                    </div>
                </div>-->
            </div>
        </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let terminales = [];
    let terminales2 = [];
    terminales =[
        ['1','DT1-1'],
        ['2','DT1-10'],
        ['3','DT1-13'],
        ['4','TT2-9'],
        ['5','DT2-802'],
        ['6','ET2-27'],
        ['7','ET1-35'],
        ['8','DT1-61'],
        ['9','DT1-34'],
        ['10','DT1-53'],
        ['11','DT1-57'],
        ['12','DT1-59'],
        ['13','DT1-7'],
        ['14','DT1-78'],
        ['15','DT1-8'],
        ['16','DT2-10'],
        ['17','DT2-100'],
        ['18','DT2-103'],
        ['19','DT2-45'],
        ['20','DT2-115'],
        ['21','DT2-116'],
        ['22','DT2-12'],
        ['23','DT2-135'],
        ['24','DT2-17'],
        ['25','DT2-16'],
        ['26','TT1-310'],
        ['27','DT2-15'],
        ['28','DT2-194'],
        ['29','DT2-22'],
        ['30','DT2-3'],
        ['31','DT2-74'],
        ['32','DT2-71'],
        ['33','DT2-37'],
        ['34','DT2-38'],
        ['35','DT1-5'],
        ['36','TT2-14'],
        ['37','DT1-5'],
        ['38','DT1-17'],
        ['39','DT1-31'],
        ['40','DT2-58'],
        ['41','DT2-71'],
        ['42','DT2-802'],
        ['43','DT2-94'],
        ['44','DT2-99'],
        ['45','TT2-14'],
        ['46','ET2-27'],
        ['47','ET2-34'],
        ['48','ET1-36'],
        ['49','ET1-37'],
        ['50','TT1-2'],
        ['51','DT1-3'],
        ['52','ET2-39'],
        ['53','ET2-6'],
        ['54','ET1-5'],
        ['55','FT1-1'],
        ['56','DT2-119'],
        ['57','FT2-5'],
        ['58','GNT1-1'],
        ['59','GNT2-1'],
        ['60','YT2-13'],
        ['61','MT1-119'],
        ['62','DT2-21'],
        ['63','MT1-2'],
        ['64','DT1-57'],
        ['65','YT2-26'],
        ['66','TT2-308'],
        ['67','MT1-4'],
        ['68','MT1-61'],
        ['69','MT2-102'],
        ['70','MT2-136'],
        ['71','MT2-3'],
        ['72','MT2-3'],
        ['73','YT2-28'],
        ['74','MT2-46'],
        ['75','MT2-55'],
        ['76','ET2-27'],
        ['77','MT2-71'],
        ['78','MT2-93'],
        ['79','PT3-10'],
        ['80','PT3-15'],
        ['81','TT2-900'],
        ['82','MT4-23'],
        ['83','TT3-104'],
        ['84','MT4-24'],
        ['85','MT4-24'],
        ['86','MT3-86'],
        ['87','PT3-7'],
        ['88','PT3-8'],
        ['89','ET1-35'],
        ['90','TT1-120'],
        ['91','TT1-281'],
        ['92','TT2-311'],
        ['93','DT2-36'],
        ['94','TT2-9'],
        ['95','TT2-231'],
        ['96','TT2-25'],
        ['97','DT1-78'],
        ['98','TT2-180'],
        ['99','TT2-65'],
        ['100','TT2-21'],
        ['101','TT2-3'],
        ['102','TT2-22'],
        ['103','TT2-22'],
        ['104','DT2-6'],
        ['105','TT2-102'],
        ['106','TT2-267'],
        ['107','TT2-55'],
        ['108','TT2-279'],
        ['109','TT2-308'],
        ['110','DT2-32'],
        ['111','TT2-9'],
        ['112','DT2-245'],
        ['113','TT2-217'],
        ['114','MT2-136'],
        ['115','TT2-64'],
        ['116','TT2-313'],
        ['117','TT2-33'],
        ['118','TT2-35'],
        ['119','TT2-431'],
        ['120','TT2-313'],
        ['121','TT2-901'],
        ['122','MT1-2'],
        ['123','DT2-22'],
        ['124','MT2-118'],
        ['125','TT1-53'],
        ['126','TT2-22'],
        ['127','TT1-103'],
        ['128','DT1-53'],
        ['129','DT1-83'],
        ['130','DT2-123'],
        ['131','DT2-119'],
        ['132','DT2-111'],
        ['133','DT2-103'],
        ['134','DT2-139'],
        ['135','DT2-17'],
        ['136','DT2-21'],
        ['137','DT2-31'],
        ['138','DT2-32'],
        ['139','DT2-39'],
        ['140','DT2-44'],
        ['141','DT2-55'],
        ['142','DT2-42'],
        ['143','DT2-66'],
        ['144','DT2-67'],
        ['145','DT2-79'],
        ['146','DT2-8'],
        ['147','DT2-690'],
        ['148','DT2-95'],
        ['149','ET2-30'],
        ['150','ET1-44'],
    ];

    terminales2 =[
        ['151','ET1-5'],
        ['152','ET2-26'],
        ['153','FT1-7'],
        ['154','MT2-1'],
        ['155','DT2-74'],
        ['156','MT2-3'],
        ['157','MT2-55'],
        ['158','PT2-1'],
        ['159','PT2-2'],
        ['160','TT2-11'],
        ['161','TT2-12'],
        ['162','TT2-137'],
        ['163','TT2-140'],
        ['164','TT2-114'],
        ['165','TT2-157'],
        ['166','TT2-187'],
        ['167','TT2-217'],
        ['168','TT2-255'],
        ['169','TT2-27'],
        ['170','TT2-310'],
        ['171','TT2-311'],
        ['172','TT2-315'],
        ['173','TT2-4'],
        ['174','TT2-63'],
        ['175','TT2-65'],
        ['176','TT2-66'],
        ['177','TT2-72'],
        ['178','TT2-90'],
        ['179','829-12077412-MR'],
        ['180','829-12048254-MR'],
        ['181','829-12124581-MR'],
        ['182','829-12077413-MR'],
        ['183','1212582-MR'],
        ['184','12089188'],
        ['185','DT2-74'],
        ['186','DT1-775'],
        ['187','FT2-4'],
        ['188','DT1-134'],
        ['189','YT2-42'],
        ['190','DT1-74'],
        ['191','DT2-126'],
        ['192','YT2-26'],
        ['193','YT1-13'],
        ['194','DT1-27'],
        ['195','DT2-85'],
        ['196','MT2-48'],
        ['197','DT2-802'],
        ['198','MTAT1-1'],
        ['199','TT3-137'],
        ['200','TT1-74'],
        ['3','DT1-3'],
        ['4','TT2-23'],
        ['8','DT1-14'],
        ['18','DT2-132'],
        ['19','DT2-11'],
        ['22','DT2-52'],
        ['24','DT2-94'],
        ['25','DT2-132'],
        ['26','TT2-311'],
        ['29','DT2-59'],
        ['35','DT2-4'],
        ['37','DT2-4'],
        ['39','DT2-14'],
        ['45','TT2-152'],
        ['46','ET1-5'],
        ['47','ET1-35'],
        ['51','DT1-87'],
        ['60','MT1-20'],
        ['64','DT1-54'],
        ['67','MT2-3'],
        ['70','MT2-132'],
        ['77','MT2-92'],
        ['82','PT1-17'],
        ['83','PT3-30'],
        ['84','MT3-87'],
        ['86','MT2-24'],
        ['89','ET2-27'],
        ['92','TT1-310'],
        ['95','TT2-15'],
        ['98','TT2-221'],
        ['99','TT2-58'],
        ['100','TT2-218'],
        ['101','TT2-219'],
        ['102','TT2-15'],
        ['107','TT2-127'],['110','DT2-111'],['111','TT2-23'],['112','TT2-170'],['113','TT2-309'],['115','TT2-18'],['117','TT2-60'],['123','DT2-59'],['127','TT1-102'],['132','DT2-112'],['135','DT2-128'],['139','DT2-127'],['148','FT2-12'],['149','AT1-9'],['152','ET1-37'],
        ['153','YT2-28'],['154','TT2-209'],['156','MT2-55'],['157','MT1-4'],['164','TT2-152'],['171','TT1-310'],['178','TT2-51'],['24','DT2-128'],['60','MT2-136'],['64','DT1-58'],['67','MT1-54'],['82','MT3-146'],['84','TT1-307'],['86','MT3-75'],['89','ET2-33'],['95','PT1-17'],['98','TT1-257'],['99','TT2-218'],
        ['101','TT2-64'],['107','FT2-5'],['113','TT2-310'],['115','TT2-31'],['127','TT2-111'],['152','ET1-25'],['154','TT2-224'],['156','MT1-54'],['178','TT2-32'],['60','MT1-131'],['64','DT1-4'],['67','MT1-78'],['82','TT3-14'],['84','MT3-117'],['86','YT3-75'],
        ['89','AT1-5'],['95','TT2-167'],['101','TT2-60'],['154','MT1-2'],['60','MT2-132'],['82','MT4-24'],['84','TT3-158'],['86','TT3-51'],['89','ET2-28'],['95','TT2-22'],['101','TT2-19'],['82','MT3-41'],['84','MT4-23'],['86','TT3-117'],['89','ET2-34'],['86','MT3-126'],['89','DT1-134'],['89','ET1-36'],['89','ET1-44'],
    ];
        terminales.forEach(function(terminal) {
            $('#terminalspormaquina').append('<tr><td>APP-' + terminal[0] + '</td><td>' + terminal[1] + '</td></tr>');
        });  
        
        terminales2.forEach(function(terminal) {
            $('#terminalspormaquina2').append('<tr><td>APP-' + terminal[0] + '</td><td>' + terminal[1] + '</td></tr>');
        });

        window.onload = function() {
            document.getElementById('totalterminales').textContent = terminales.length;
            document.getElementById('totalterminales2').textContent = terminales2.length;
        };
        const scrollStep = 150;
const scrollInterval = 5000;

setInterval(() => {

    const currentPosition = window.scrollY;
    const maxScroll =
        document.documentElement.scrollHeight - window.innerHeight;

    if (currentPosition >= maxScroll - 5) {

        // Llegó al final → regresar arriba
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    } else {

        // Bajar 50 px
        window.scrollBy({
            top: scrollStep,
            behavior: 'smooth'
        });

    }

}, scrollInterval);
    

</script>