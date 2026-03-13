// 1. Inicializar el Grafo (El modelo de datos) y el Paper (La vista)
const graph = new joint.dia.Graph();

const paper = new joint.dia.Paper({
    el: document.getElementById('lienzo-arnes'),
    model: graph,
    width: 1000,
    height: 600,
    gridSize: 10,
    drawGrid: true,
    background: { color: '#ffffff' },
    defaultLink: new joint.shapes.standard.Link({
        attrs: {
            line: { stroke: '#34495e', strokeWidth: 3 }
        }
    }),
    // Permite que el usuario dibuje cables arrastrando desde un componente
    interactive: { linkMove: true }
});

// 2. Función para crear un Conector
function crearConector(x, y, nombre, partNumber) {
    const rect = new joint.shapes.standard.Rectangle();
    rect.position(x, y);
    rect.resize(120, 50);
    rect.attr({
        body: { fill: '#3498db', rx: 5, ry: 5, stroke: 'none' },
        label: { text: nombre, fill: 'white', fontWeight: 'bold' }
    });
    
    // Inyectamos data personalizada para el BOM
    rect.set('bomData', {
        type: 'Connector',
        partNumber: partNumber,
        qty: 1
    });

    // Agregamos "Puertos" (opcional para uniones más precisas) o permitimos magnetismo general
    rect.addTo(graph);
    return rect;
}

// 3. Función para crear un Splice
function crearSplice(x, y) {
    const circle = new joint.shapes.standard.Circle();
    circle.position(x, y);
    circle.resize(40, 40);
    circle.attr({
        body: { fill: '#e74c3c', stroke: 'none' },
        label: { text: 'SP', fill: 'white' }
    });
    
    circle.set('bomData', { type: 'Splice', qty: 1 });
    circle.addTo(graph);
    return circle;
}

// 4. Interacción con los botones de la UI
document.getElementById('btn-add-connector').addEventListener('click', () => {
    crearConector(50, 50, 'Conector 6-Way', '12040953');
});

document.getElementById('btn-add-splice').addEventListener('click', () => {
    crearSplice(200, 200);
});

// 5. Detectar cuando un usuario crea un cable (Link) para pedirle la medida
graph.on('add', function(cell) {
    if (cell.isLink()) {
        // Evento que se dispara cuando se dibuja una nueva arista
        cell.on('change:target', function(link) {
            if (link.get('target').id) {
                // El cable se conectó a un destino válido
                let longitud = prompt("Ingrese la longitud del cable (mm):", "150");
                let calibreColor = prompt("Ingrese Calibre y Color (ej. 18 RED):", "18 RED");
                
                // Agregamos la etiqueta visual al cable
                link.appendLabel({
                    attrs: {
                        text: { text: `${longitud}mm\n${calibreColor}`, fill: '#2c3e50', fontSize: 12 },
                        rect: { fill: '#ecf0f1', stroke: '#bdc3c7', strokeWidth: 1, rx: 3, ry: 3 }
                    }
                });

                // Guardamos los datos técnicos para la exportación
                link.set('bomData', {
                    type: 'Wire',
                    length: parseInt(longitud),
                    specs: calibreColor
                });
            }
        });
    }
});

// 6. Exportar el diseño completo a JSON para enviarlo al Backend
document.getElementById('btn-exportar').addEventListener('click', () => {
    const diseñoCompleto = graph.toJSON();
    console.log("Datos listos para enviar al servidor:", diseñoCompleto);
    
    // Aquí puedes usar fetch() o axios.post() para enviar 'diseñoCompleto' 
    // a una ruta de tu API y procesar el Excel.
    alert("Revisa la consola. El objeto JSON está listo para enviarse por Axios/Fetch.");
});