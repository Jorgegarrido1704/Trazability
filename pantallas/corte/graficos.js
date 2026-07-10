var datos ;
var pareto ;
var Qdays;
var colorQ;
var labelQ;
var pyl=Object.keys(paretoYear);
var pyv=Object.values(paretoYear);
var employees =empleados.map(empleados => empleados.Responsable);
var empvalues = empleados.map(empleados => empleados.errores);


   function guardarDateQ(){
    const date =document.getElementById("dateIncidence").value;
    //alert(date);
    document.getElementById("gQ").value = date;
    alert("Se ha guardado la Incidencia de la fecha " + document.getElementById("gQ").value);
    document.getElementById("bQ").value = "";
    document.getElementById("guardasDateQ").submit;
   }
   function borrarDateQ(){
    const date =document.getElementById("dateIncidence").value;
    //alert(date);
    document.getElementById("gQ").value = "";
    //alert(document.getElementById("gQ").value);
    document.getElementById("bQ").value = date;
    alert("Se ha borrado la Incidencia de la fecha " + document.getElementById("bQ").value);
    document.getElementById("borrarDateQ").submit;
   }


var totalp=[];
var paretoKeys = Object.keys(pareto);
var paretoValues = Object.values(pareto);
var totalParetos =paretoKeys.length;
for (var i = 0; i < totalParetos; i++) {
totalp[i]=97;
}
var keys = Object.keys(datos);
var values = Object.values(datos);
var color=color1 = [];

for (var i = 0; i < keys.length; i++) {
var red = Math.floor(Math.random() * 256);
var blue = Math.floor(Math.random() * 256);
var green = Math.floor(Math.random() * 256);
color.push("rgb(" + red + "," + blue + "," + green + ")");
}
for (var i = 0; i < keys.length; i++) {
var red1 = Math.floor(Math.random() * 256);
var blue1 = Math.floor(Math.random() * 256);
var green1 = Math.floor(Math.random() * 256);
color1.push("rgb(" + red + "," + blue + "," + green + ")");
}

var ctx1 = document.getElementById("BarCali");
var Calibar = new Chart(ctx1, {
type: 'bar',
data: {
    labels: keys,
    datasets: [{
        data:values,
        backgroundColor: color,
        hoverBackgroundColor:color1,
        hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
},
options: {

    maintainAspectRatio: false,
    tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
    },
    legend: {
        display: false
    },
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true,
                min: 0,
                stepSize: 1,
            },
            gridLines: {
                color: '#e3e3e3',
                drawBorder: false,
            },
        }],
        xAxes: [{
            gridLines: {
                display: false,
            },
        }]
    }
},
});


var ctx2 = document.getElementById("pareto");
var calidadPareto = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: paretoKeys,
        datasets: [
            {
                label: 'FTQ',
                data: paretoValues,
                backgroundColor: ['#1cc88a', 'red'],
                hoverBackgroundColor: ['#1cc89a', '#ff1a1a'],
                borderColor: '#1cc88a',
                fill: false, // Evita rellenar el área debajo de la línea
                borderWidth: 4
            },
            {
                label: 'Goal',
                data: totalp,
                borderColor: '#FFCC00',
                borderDash: [5, 5],
                pointRadius: 0,
                fill: false,
                borderWidth: 2
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        legend: {
            display: true
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    min: 90,
                    max: 100,
                    stepSize: 1,
                },
                gridLines: {
                    color: '#e3e3e3',
                    drawBorder: false,
                },
            }],
            xAxes: [{
                gridLines: {
                    display: false,
                },
            }]
        }
    }
});

//Bar pareto
//console.log(pareto);
var ctx2 = document.getElementById("barPareto");
var calidadPareto = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: pyl,
        datasets: [
            {
                label: 'FTQ',
                data: pyv,
                backgroundColor: ['#1cc88a', 'red','yellow','blue'],
                hoverBackgroundColor: ['#1cc89a', 'red','yellow','blue'],
                borderColor: '#1cc88a',
                fill: false, // Evita rellenar el área debajo de la línea
                borderWidth: 1
            },]
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        responsive: true,
        plugins: {
            legend: {
                display: true, // Mostrar leyendas
                labels: {
                    usePointStyle: true, // Cambiar íconos de las etiquetas a cuadrados
                    pointStyle: 'rect', // Configura los íconos a rectángulos
                    generateLabels: function(chart) {
                        const datasets = chart.data.datasets[0];
                        return chart.data.labels.map((label, index) => ({
                            text: label,
                            fillStyle: datasets.backgroundColor[index], // Color del cuadro
                            strokeStyle: datasets.backgroundColor[index], // Color del borde
                            hidden: false
                        }));
                    }
                }
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    min: 95, // Limitar mínimo
                    max: 100, // Limitar máximo
                    stepSize: .5, // Intervalos de 5 en 5
                },
                gridLines: {
                    color: '#e3e3e3',
                    drawBorder: false,
                },
            }],
            xAxes: [{
                gridLines: {
                    display: false,
                },
            }]
        }
    }
});

// Set default font family and color
Chart.defaults.global.defaultFontFamily = 'Nunito';
//Q as Quality

var ctxQ = document.getElementById("Q");
var myPieChart = new Chart(ctxQ, {
type: 'doughnut',
data: {
labels: labelQ,
datasets: [{
  data: Qdays,
  backgroundColor: colorQ,
 // hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
  hoverBorderColor: "rgba(234, 236, 244, 1)",
}],
},
options: {
maintainAspectRatio: false,
tooltips: {
  backgroundColor: "rgb(255,255,255)",
  bodyFontColor: "#858796",
  borderColor: '#dddfeb',
  borderWidth: 1,
  xPadding: 15,
  yPadding: 15,
  displayColors: false,
  caretPadding: 10,
},
legend: {
  display: false
},
cutoutPercentage: 80,
},

});


var incidencias = [];
var inc = document.getElementById("MonthIncidences");
var incs = new Chart(inc, {
    type: 'bar',
    data: {
        labels: employees,
        datasets: [
            {
                label: 'Incidencias del mes',
                data: empvalues,
                backgroundColor:[
  'rgba(200, 48, 28, 1)',
  'rgba(200, 94, 28, 1)',
  'rgba(200, 117, 28, 1)',
  'rgba(200, 131, 28, 1)',
  'rgba(210, 150, 28, 1)',
  'rgba(200, 154, 28, 1)',
  'rgba(194, 200, 28, 1)',
  'rgba(188, 200, 28, 1)',
  'rgba(171, 200, 28, 1)',
  'rgba(151, 200, 28, 1)'
],
                fill: false, // Evita rellenar el área debajo de la línea
                borderWidth: 4
            },

        ]
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,

        },
        legend: {
            display: true
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    min: 0,
                    max: 30,
                    stepSize: 10,
                },
                gridLines: {
                    color: '#e3e3e3',
                    drawBorder: false,
                },
            }],
            xAxes: [{
                gridLines: {
                    display: false,
                },
            }]
        }
    }
});

try {


var incidencias = [];
var supnames = Object.keys(supIssue);
var supvalues = Object.values(supIssue);
var sups = document.getElementById("Supervisorissues");
var supss = new Chart(sups, {
    type: 'bar',
    data: {
        labels: supnames,
        datasets: [
            {
                label: 'Incidencias del mes',
                data: supvalues,
                backgroundColor:[
  'rgba(200, 48, 28, 1)',
  'rgba(200, 94, 28, 1)',
  'rgba(200, 117, 28, 1)',
  'rgba(200, 131, 28, 1)',
  'rgba(210, 150, 28, 1)',
  'rgba(200, 154, 28, 1)',
  'rgba(194, 200, 28, 1)',
  'rgba(188, 200, 28, 1)',
  'rgba(171, 200, 28, 1)',
  'rgba(151, 200, 28, 1)'
],
                fill: false, // Evita rellenar el área debajo de la línea
                borderWidth: 4
            },

        ]
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,

        },
        legend: {
            display: true
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    min: 1,
                    max: 150,
                    stepSize: 10,
                },
                gridLines: {
                    color: '#e3e3e3',
                    drawBorder: false,
                },
            }],
            xAxes: [{
                gridLines: {
                    display: false,
                },
            }]
        }
    }
    });
}catch (error) {
    console.log("No hay fecha guardada");
}
