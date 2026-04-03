  // ==========================================
        // 1. CONFIGURACIÓN BASE Y PESTAÑAS
        // ==========================================
        function abrirTab(idTab, btn) { 
            $('.tab-content').removeClass('active'); $('.tab-btn').removeClass('active'); 
            $('#tab-' + idTab).addClass('active'); $(btn).addClass('active'); 
            if (idTab === 'rutas') generarReporteRutas(); 
            if (idTab === 'bom') generarReporteBOM(); 
            if (idTab === 'splices') generarReporteSplices(); 
        }
        
        function evaluarMatematica(expresion) { try { let expLimpia = String(expresion).replace(/[^0-9\+\-\.\s]/g, ''); if(expLimpia.trim() === '') return 0; return Function('"use strict";return (' + expLimpia + ')')(); } catch (e) { return 0; } }

        let escalaActual = 1; let contSplices = 1; const graph = new joint.dia.Graph(); let elementoRedimensionando = null; 
        
        const paper = new joint.dia.Paper({
            el: document.getElementById('lienzo-arnes'), model: graph, width: 3000, height: 2000, gridSize: 10, drawGrid: true, background: { color: '#ffffff' },
            defaultLink: new joint.shapes.standard.Link({ attrs: { line: { stroke: '#34495e', strokeWidth: 3, strokeLinejoin: 'round', strokeLinecap: 'round' } }, router: { name: 'manhattan', args: {step: 10, padding: 20} }, connector: { name: 'rounded', args: { radius: 10 } } }), 
            interactive: function(cellView) {
                if (cellView.model.isLink() && cellView.model.get('locked')) return { vertexAdd: false, vertexMove: false, vertexRemove: false, linkMove: false };
                return { linkMove: true, vertexAdd: true, vertexMove: true, vertexRemove: true };
            },
            linkPinning: false, snapLinks: { radius: 30 },
            validateConnection: function(cellViewS, magnetS, cellViewT, magnetT) { if (cellViewS === cellViewT) return false; return magnetT != null; }
        });

        // ==========================================
        // 2. DICCIONARIOS DE COLORES
        // ==========================================
        const loomColors = { 'corrugado': { fill: '#95a5a6', stroke: '#7f8c8d' }, 'manga': { fill: '#2ecc71', stroke: '#27ae60' }, 'pvc': { fill: '#f1c40f', stroke: '#f39c12' } };
        const tieColors = { 'cincho': { fill: '#2c3e50', stroke: '#1a252f' }, 'cinta': { fill: '#111111', stroke: '#000000' } }; 

        function getWireColorHex(colorCode) {
            if(!colorCode) return '#34495e';
            const c = colorCode.toUpperCase();
            if(c.includes('BK') || c.includes('BLK') || c.includes('BLACK')) return '#212121';
            if(c.includes('RD') || c.includes('RED')) return '#e74c3c';
            if(c.includes('BU') || c.includes('BLU')) return '#3498db';
            if(c.includes('YE') || c.includes('YEL')) return '#f1c40f';
            if(c.includes('GN') || c.includes('GRN')) return '#2ecc71';
            if(c.includes('VT') || c.includes('VIO') || c.includes('PUR')) return '#9b59b6';
            if(c.includes('BN') || c.includes('BRN')) return '#8d6e63';
            if(c.includes('GY') || c.includes('GRY')) return '#95a5a6';
            if(c.includes('WH') || c.includes('WHT')) return '#bdc3c7'; 
            if(c.includes('ORG')) return '#e67e22';
            if(c.includes('PNK')) return '#ff9ff3';
            return '#34495e'; 
        }

        // ==========================================
        // 3. FUNCIONES CREADORAS DE FIGURAS
        // ==========================================
        function getConfigConector(orientacion, vias) {
            let config = { width: 140, height: Math.max(60, vias * 25), labelRefY: 15, labelRefX: 70, portLabelPos: { name: 'left', args: { x: -15 } } };
            if (orientacion === 'left') { config.portLabelPos = { name: 'right', args: { x: 15 } }; } else if (orientacion === 'top') { config.width = Math.max(140, vias * 30); config.height = 80; config.labelRefY = 55; config.portLabelPos = { name: 'bottom', args: { y: 15 } }; } else if (orientacion === 'bottom') { config.width = Math.max(140, vias * 30); config.height = 80; config.labelRefY = 15; config.portLabelPos = { name: 'top', args: { y: -15 } }; }
            return config;
        }

        function crearConector(x, y, descripcion, partNumber, numVias, orientacion = 'right') {
            let pines = []; let terminalesIniciales = {}; for(let i = 1; i <= numVias; i++) { pines.push({ id: `via-${i}`, group: 'cavidades', attrs: { text: { text: `${i}` } } }); terminalesIniciales[`via-${i}`] = '41412'; }
            let cnf = getConfigConector(orientacion, numVias);
            const rect = new joint.shapes.standard.Rectangle({ position: { x: x, y: y }, size: { width: cnf.width, height: cnf.height }, attrs: { body: { fill: '#3498db', rx: 5, ry: 5, stroke: '#2980b9', strokeWidth: 2 }, label: { text: `${descripcion}\nPN: ${partNumber}`, fill: 'white', fontWeight: 'bold', fontSize: 12, refY: cnf.labelRefY, refX: '50%' } }, ports: { groups: { 'cavidades': { position: orientacion, attrs: { circle: { r: 7, magnet: true, fill: '#f1c40f', stroke: '#d35400', strokeWidth: 2 }, text: { fill: '#ffffff', fontSize: 11, fontWeight: 'bold' } }, label: { position: cnf.portLabelPos } } }, items: pines } });
            rect.set('bomData', { type: 'Connector', description: descripcion, housingPartNumber: partNumber, cavities: numVias, terminals: terminalesIniciales, orientacion: orientacion }); rect.addTo(graph); return rect;
        }

        function crearSplice(x, y, nombre) { 
            const circle = new joint.shapes.standard.Circle({ position: { x: x, y: y }, size: { width: 50, height: 50 }, attrs: { body: { fill: '#e74c3c', stroke: '#c0392b', strokeWidth: 2 }, label: { text: nombre, fill: '#c0392b', fontWeight: 'bold', fontSize: 13, textVerticalAnchor: 'bottom', refY: -5, pointerEvents: 'none' } }, ports: { groups: { 'conexion': { position: 'center', attrs: { circle: { r: 12, magnet: true, fill: '#f1c40f', stroke: '#d35400', strokeWidth: 2, cursor: 'crosshair' } } } }, items: [ { id: nombre, group: 'conexion' } ] } }); 
            circle.set('bomData', { type: 'Splice', name: nombre, qty: 1 }); circle.addTo(graph); return circle; 
        }
        
        function crearLoomDinamico(x, y, w, h) { 
            const colores = loomColors['corrugado'];
            const rect = new joint.shapes.standard.Rectangle({ 
                markup: [{ tagName: 'rect', selector: 'body' }, { tagName: 'text', selector: 'label' }, { tagName: 'polygon', selector: 'resizeHandle' }],
                position: { x: x, y: y }, size: { width: w, height: h }, 
                attrs: { 
                    body: { fill: colores.fill, fillOpacity: 0.4, stroke: colores.stroke, strokeWidth: 2, strokeDasharray: '4,4' }, 
                    label: { text: 'NUEVO RECUBRIMIENTO', fill: '#2c3e50', fontWeight: 'bold', fontSize: 11 },
                    resizeHandle: { points: '0,15 15,15 15,0', fill: '#34495e', cursor: 'nwse-resize', refX: '100%', refDx: -15, refY: '100%', refDy: -15 }
                } 
            }); 
            rect.set('bomData', { type: 'Loom', recubrimiento: 'corrugado', description: 'Tubo Corrugado', qty: 1, len: w, wid: h, orientacion: 'horizontal' }); rect.addTo(graph); rect.toBack(); return rect; 
        }

        function crearTieDinamico(x, y, w, h) {
            const rect = new joint.shapes.standard.Rectangle({ 
                markup: [{ tagName: 'rect', selector: 'body' }, { tagName: 'text', selector: 'label' }, { tagName: 'polygon', selector: 'resizeHandle' }],
                position: { x: x, y: y }, size: { width: w, height: h }, 
                attrs: { 
                    body: { fill: '#2c3e50', rx: 3, ry: 3, stroke: '#1a252f', strokeWidth: 2 }, 
                    label: { text: 'TIE', fill: '#ffffff', fontSize: 8, fontWeight: 'bold', transform: 'rotate(-90)' },
                    resizeHandle: { points: '0,10 10,10 10,0', fill: '#ffffff', cursor: 'nwse-resize', refX: '100%', refDx: -10, refY: '100%', refDy: -10 }
                } 
            });
            rect.set('bomData', { type: 'Tie', recubrimiento: 'cincho', description: 'Cincho Plástico', qty: 1, orientacion: 'vertical' }); rect.addTo(graph); rect.toFront(); return rect;
        }

        function crearBreakPoint(x, y) {
            const circle = new joint.shapes.standard.Circle({ position: { x: x, y: y }, size: { width: 20, height: 20 }, attrs: { body: { fill: '#34495e', stroke: '#000000', strokeWidth: 3, strokeDasharray: '4,2' }, label: { text: 'BP', fill: '#ffffff', fontSize: 9, fontWeight: 'bold' } } });
            circle.set('bomData', { type: 'BreakPoint', orientacion: 'horizontal', description: 'Tape-25', qty: 6, unit: 'inches' }); circle.addTo(graph); circle.toBack(); return circle;
        }

        // ==========================================
        // 4. EVENTOS DEL RATÓN (DIBUJAR, REDIMENSIONAR, ARRASTRAR ETIQUETAS)
        // ==========================================
        let drawingState = { active: false, type: '', startX: 0, startY: 0, shape: null };

        $('#btn-add-loom').click(() => { drawingState = { active: true, type: 'Loom', startX: 0, startY: 0, shape: null }; $('#lienzo-arnes').css('cursor', 'crosshair'); });
        $('#btn-add-tie').click(() => { drawingState = { active: true, type: 'Tie', startX: 0, startY: 0, shape: null }; $('#lienzo-arnes').css('cursor', 'crosshair'); });

        $('#btn-add-break').click(() => { drawingState.active = false; $('#lienzo-arnes').css('cursor', 'default'); crearBreakPoint(300, 200); });
        $('#btn-add-connector').click(() => { drawingState.active = false; $('#lienzo-arnes').css('cursor', 'default'); crearConector(50, 50, 'Conector A', '12064754', 4, 'right'); });
        $('#btn-add-splice').click(() => { drawingState.active = false; $('#lienzo-arnes').css('cursor', 'default'); crearSplice(300, 150, `SP-${contSplices}`); contSplices++; });

        paper.on('blank:pointerdown', function(evt, x, y) {
            if (drawingState.active) {
                drawingState.startX = x; drawingState.startY = y;
                if(drawingState.type === 'Loom') drawingState.shape = crearLoomDinamico(x, y, 1, 1);
                else if(drawingState.type === 'Tie') drawingState.shape = crearTieDinamico(x, y, 1, 1);
            }
        });

        paper.on('blank:pointermove', function(evt, x, y) {
            if (drawingState.active && drawingState.shape) {
                let newX = Math.min(x, drawingState.startX); let newY = Math.min(y, drawingState.startY);
                let newWidth = Math.max(Math.abs(x - drawingState.startX), 1); let newHeight = Math.max(Math.abs(y - drawingState.startY), 1);
                drawingState.shape.position(newX, newY); drawingState.shape.resize(newWidth, newHeight);
            }
        });

        paper.on('blank:pointerup', function(evt, x, y) {
            if (drawingState.active && drawingState.shape) {
                let bbox = drawingState.shape.getBBox(); let isLoom = drawingState.type === 'Loom';
                if (bbox.width < 15 && bbox.height < 15) { drawingState.shape.resize(isLoom ? 200 : 15, isLoom ? 40 : 50); bbox = drawingState.shape.getBBox(); }
                let d = drawingState.shape.get('bomData'); d.orientacion = bbox.width >= bbox.height ? 'horizontal' : 'vertical';
                if(isLoom) { d.len = Math.round(bbox.width >= bbox.height ? bbox.width : bbox.height); d.wid = Math.round(bbox.width >= bbox.height ? bbox.height : bbox.width); }
                drawingState.shape.set('bomData', d);
                if(!isLoom) { drawingState.shape.attr('label/transform', d.orientacion === 'vertical' ? 'rotate(-90)' : 'rotate(0)'); }

                let formaCreada = drawingState.shape; drawingState.active = false; drawingState.shape = null; $('#lienzo-arnes').css('cursor', 'default');
                if(isLoom) abrirModalLoom(formaCreada); else abrirModalTie(formaCreada);
            }
        });

        // Redimensión de Mangas
        paper.on('element:pointerdown', function(elementView, evt, x, y) {
            const target = evt.target; const selector = target.getAttribute('joint-selector') || target.getAttribute('selector');
            if (selector === 'resizeHandle') { elementoRedimensionando = elementView.model; evt.stopPropagation(); }
        });

        paper.on('blank:pointermove element:pointermove', function(evt, x, y) {
            if (elementoRedimensionando) {
                const bbox = elementoRedimensionando.getBBox(); const newWidth = Math.max(20, x - bbox.x); const newHeight = Math.max(20, y - bbox.y);
                elementoRedimensionando.resize(newWidth, newHeight);
                const d = elementoRedimensionando.get('bomData');
                if (d && (d.type === 'Loom' || d.type === 'Tie')) {
                    d.orientacion = newWidth >= newHeight ? 'horizontal' : 'vertical';
                    if(d.type === 'Loom') { d.len = newWidth >= newHeight ? newWidth : newHeight; d.wid = newWidth >= newHeight ? newHeight : newWidth; }
                    elementoRedimensionando.set('bomData', d);
                }
            }
        });

        $(window).on('mouseup', function() {
            if (elementoRedimensionando) { actualizarEscaneoLooms(); elementoRedimensionando = null; }
            if (dragLabelState.active) dragLabelState.active = false;
        });

        // Arrastrar etiquetas de Cables
        let dragLabelState = { active: false, linkView: null, startX: 0, startY: 0, initialOffset: {x:0, y:0} };

        paper.on('link:pointerdown', function(linkView, evt, x, y) {
            const target = evt.target;
            if (target.tagName === 'rect' && target.parentNode && target.parentNode.classList.contains('label')) {
                evt.stopPropagation(); 
                dragLabelState.active = true; dragLabelState.linkView = linkView; dragLabelState.startX = x; dragLabelState.startY = y;
                const labels = linkView.model.labels();
                if (labels.length > 0 && labels[0].position && labels[0].position.offset) {
                    let offset = labels[0].position.offset;
                    dragLabelState.initialOffset = (typeof offset === 'object') ? { x: offset.x || 0, y: offset.y || 0 } : { x: 0, y: offset };
                } else { dragLabelState.initialOffset = { x: 0, y: -20 }; }
            } else {
                paper.removeTools();
                if (linkView.model.get('locked')) linkView.model.set('locked', false);
                const toolsView = new joint.dia.ToolsView({ tools: [new joint.linkTools.Vertices(), new joint.linkTools.Segments()] });
                linkView.addTools(toolsView);
            }
        });

        $(document).on('mousemove', function(evt) {
            if (dragLabelState.active) {
                const localPoint = paper.clientToLocalPoint({ x: evt.clientX, y: evt.clientY });
                const dx = localPoint.x - dragLabelState.startX; const dy = localPoint.y - dragLabelState.startY;
                dragLabelState.linkView.model.prop('labels/0/position/offset', { x: dragLabelState.initialOffset.x + dx, y: dragLabelState.initialOffset.y + dy });
            }
        });

        paper.on('blank:pointerclick element:pointerclick', function() { paper.removeTools(); });

        // ==========================================
        // 5. IMPORTACIÓN EXCEL
        // ==========================================
        $('#input-import-excel').on('change', function(e) {
            const file = e.target.files[0]; if(!file) return; const reader = new FileReader();
            reader.onload = function(evt) {
                try {
                    const data = new Uint8Array(evt.target.result); const workbook = XLSX.read(data, {type: 'array'});
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]]; const jsonData = XLSX.utils.sheet_to_json(firstSheet);
                    procesarListaDeCorteExcel(jsonData);
                } catch (err) { alert("Error al leer el archivo Excel."); }
            };
            reader.readAsArrayBuffer(file); $(this).val(''); 
        });

        function procesarListaDeCorteExcel(datos) {
            if (datos.length === 0) return alert("El archivo Excel está vacío.");
            if(!confirm("Esto borrará el diseño actual. ¿Deseas continuar?")) return;
            graph.clear(); contSplices = 1;

            let conectoresMap = {}; 
            datos.forEach(row => {
                let orig = row['ORIGEN'] || row['DESTINO 1'] || row['FROM']; let dest = row['DESTINO'] || row['DESTINO 2'] || row['TO'];
                let pinOrig = parseInt(row['PIN_ORIGEN'] || row['PIN 1'] || 1); let pinDest = parseInt(row['PIN_DESTINO'] || row['PIN 2'] || 1);
                if(orig) { orig = String(orig).trim(); if(!conectoresMap[orig]) conectoresMap[orig] = { type: orig.startsWith('SP') ? 'Splice' : 'Connector', maxPin: 0 }; if(pinOrig > conectoresMap[orig].maxPin) conectoresMap[orig].maxPin = pinOrig; }
                if(dest) { dest = String(dest).trim(); if(!conectoresMap[dest]) conectoresMap[dest] = { type: dest.startsWith('SP') ? 'Splice' : 'Connector', maxPin: 0 }; if(pinDest > conectoresMap[dest].maxPin) conectoresMap[dest].maxPin = pinDest; }
            });

            let celdasCreadas = {}; let currentX = 100, currentY = 100;
            Object.keys(conectoresMap).forEach((nombre, index) => {
                let info = conectoresMap[nombre]; let orientacion = (index % 2 === 0) ? 'right' : 'left'; 
                if(info.type === 'Splice') { celdasCreadas[nombre] = crearSplice(currentX, currentY + 50, nombre); } 
                else { celdasCreadas[nombre] = crearConector(currentX, currentY, nombre, 'Importado', Math.max(info.maxPin, 2), orientacion); }
                currentY += 250; if(currentY > 1200) { currentY = 100; currentX += 450; }
            });

            datos.forEach(row => {
                let orig = String(row['ORIGEN'] || row['DESTINO 1'] || row['FROM']).trim(); let dest = String(row['DESTINO'] || row['DESTINO 2'] || row['TO']).trim();
                let pinOrig = row['PIN_ORIGEN'] || row['PIN 1'] || 1; let pinDest = row['PIN_DESTINO'] || row['PIN 2'] || 1;

                if(orig && dest && celdasCreadas[orig] && celdasCreadas[dest]) {
                    let sourceObj = { id: celdasCreadas[orig].id, port: conectoresMap[orig].type === 'Connector' ? `via-${pinOrig}` : orig };
                    let targetObj = { id: celdasCreadas[dest].id, port: conectoresMap[dest].type === 'Connector' ? `via-${pinDest}` : dest };

                    const cal = String(row['CALIBRE'] || row['AWG'] || '18').trim();
                    const tipo = row['TIPO'] || row['TIPO DE CABLE'] || 'TXL';
                    const color = row['COLOR'] || 'BK';
                    const longitud = row['LONGITUD'] || row['LONGITUD (MM)'] || 150;
                    const circ = row['CIRCUITO'] || row['ESTAMPADO'] || 'S/N';
                    const hexColor = getWireColorHex(color);

                    const link = new joint.shapes.standard.Link({ source: sourceObj, target: targetObj, attrs: { line: { stroke: hexColor, strokeWidth: 3, strokeLinejoin: 'round', strokeLinecap: 'round' } }, router: { name: 'manhattan', args: {step: 10, padding: 20} }, connector: { name: 'rounded', args: { radius: 10 } } });
                    link.appendLabel({ attrs: { text: { text: `[${circ}]\n${longitud}mm\n${cal} AWG ${tipo} ${color}`, fill: '#2c3e50', fontSize: 11, fontWeight: 'bold' }, rect: { fill: '#ecf0f1', stroke: '#bdc3c7', strokeWidth: 1, rx: 3, ry: 3, padding: 5, cursor: 'move' } }, position: { distance: 0.5, offset: {x: 0, y: -20} } });
                    link.set('bomData', { type: 'Wire', circuito: circ, insulation: tipo, gage: cal, color: color, length: parseInt(longitud)||150 });
                    link.addTo(graph);
                }
            });
            alert("¡Importación exitosa!");
        }

        // ==========================================
        // 6. LÓGICA DE MODALES Y GUARDADO
        // ==========================================
        let enlaceActual = null; let nodoActual = null; let terminalesTemp = {}; 

        function renderizarInputsTerminales(vias) { let html = ''; for(let i = 1; i <= vias; i++) { let idVia = `via-${i}`; let valorPN = terminalesTemp[idVia] || ''; html += `<div class="cavity-row"><label>Vía ${i}:</label><input type="text" class="input-terminal" data-via="${idVia}" value="${valorPN}" placeholder="Part No. de Terminal"></div>`; } $('#lista-terminales').html(html); }
        $('#conn-vias').on('input', function() { let nuevasVias = parseInt($(this).val()) || 1; $('.input-terminal').each(function() { terminalesTemp[$(this).data('via')] = $(this).val(); }); renderizarInputsTerminales(nuevasVias); });
        
        function abrirModalCable(link) { enlaceActual = link; const d = link.get('bomData'); if(d) { $('#cable-circuito').val(d.circuito || ''); $('#cable-tipo').val(d.insulation); $('#cable-calibre').val(d.gage); $('#cable-color').val(d.color); $('#cable-longitud').val(d.length); } else { $('#cable-circuito').val(''); $('#cable-longitud').val('150'); } $('#overlay, #modal-cable').show(); }
        function abrirModalConector(element) { nodoActual = element; const d = element.get('bomData'); $('#conn-desc').val(d.description); $('#conn-pn').val(d.housingPartNumber); $('#conn-vias').val(d.cavities); $('#conn-orientacion').val(d.orientacion || 'right'); terminalesTemp = Object.assign({}, d.terminals); renderizarInputsTerminales(d.cavities); $('#overlay, #modal-conector').show(); }
        function abrirModalSplice(element) { nodoActual = element; const d = element.get('bomData'); $('#splice-name').val(d.name); $('#overlay, #modal-splice').show(); }
        function abrirModalBreak(element) { nodoActual = element; $('#overlay, #modal-break').show(); }
        function abrirModalLoom(element) { 
            nodoActual = element; 
            const d = element.get('bomData'); 
            $('#loom-tipo').val(d.recubrimiento || 'corrugado'); 
            $('#loom-qty').val(d.qty || 1); 
            $('#loom-length').val(d.len || 200); 
            $('#loom-width').val(d.wid || 40); 
            $('#loom-orientacion').val(d.orientacion || 'horizontal'); 
            $('#overlay, #modal-loom').show(); 
        }
        function abrirModalTie(element) { nodoActual = element; const d = element.get('bomData'); $('#tie-tipo').val(d.recubrimiento || 'cincho'); $('#tie-desc').val(d.description); $('#tie-qty').val(d.qty || 1); $('#tie-orientacion').val(d.orientacion); $('#overlay, #modal-tie').show(); }
        
        function cerrarModales() { $('#overlay, .modal').hide(); if (enlaceActual && !enlaceActual.get('bomData')) { enlaceActual.remove(); } enlaceActual = null; nodoActual = null; }

        $('#btn-delete-cable').click(() => { if(enlaceActual) { enlaceActual.remove(); } cerrarModales(); }); 
        $('#btn-delete-conn').click(() => { if(nodoActual) { nodoActual.remove(); } cerrarModales(); }); 
        $('#btn-delete-splice').click(() => { if(nodoActual) { nodoActual.remove(); } cerrarModales(); }); 
        $('#btn-delete-loom').click(() => { if(nodoActual) { nodoActual.remove(); } cerrarModales(); });
        $('#btn-delete-tie').click(() => { if(nodoActual) { nodoActual.remove(); } cerrarModales(); });
        $('#btn-delete-break').click(() => { if(nodoActual) { nodoActual.remove(); } cerrarModales(); });

        $('#btn-save-cable').click(() => { 
            if (enlaceActual) { 
                const circuito = $('#cable-circuito').val() || 'S/N'; const tipo = $('#cable-tipo').val(); const cal = String($('#cable-calibre').val()).trim(); const color = $('#cable-color').val(); const strLongitud = $('#cable-longitud').val(); const longitudCalculada = evaluarMatematica(strLongitud); 
                const hexColor = getWireColorHex(color); enlaceActual.attr('line/stroke', hexColor);
                enlaceActual.labels([]); enlaceActual.appendLabel({ attrs: { text: { text: `[${circuito}]\n${longitudCalculada}mm\n${cal} AWG ${tipo} ${color}`, fill: '#2c3e50', fontSize: 11, fontWeight: 'bold' }, rect: { fill: '#ecf0f1', stroke: '#bdc3c7', strokeWidth: 1, rx: 3, ry: 3, padding: 5, cursor: 'move' } }, position: { distance: 0.5, offset: {x: 0, y: -20} } }); 
                enlaceActual.set('bomData', { type: 'Wire', circuito: circuito, insulation: tipo, gage: cal, color: color, length: longitudCalculada }); 
            } cerrarModales(); actualizarEscaneoLooms();
        });
        
        $('#btn-save-conn').click(() => { 
            if (nodoActual) { 
                const desc = $('#conn-desc').val(), pn = $('#conn-pn').val(), vias = parseInt($('#conn-vias').val()), orientacion = $('#conn-orientacion').val();
                let terminalesFinales = {}; $('.input-terminal').each(function() { let pnTerminal = $(this).val(); if(pnTerminal.trim() !== '') { terminalesFinales[$(this).data('via')] = pnTerminal; } }); 
                let d = nodoActual.get('bomData'); d.description = desc; d.housingPartNumber = pn; d.cavities = vias; d.terminals = terminalesFinales; d.orientacion = orientacion; nodoActual.set('bomData', d); 
                let cnf = getConfigConector(orientacion, vias); nodoActual.attr('label/text', `${desc}\nPN: ${pn}`); nodoActual.attr('label/refY', cnf.labelRefY); nodoActual.resize(cnf.width, cnf.height); 
                let pines = []; for(let i = 1; i <= vias; i++) { pines.push({ id: `via-${i}`, group: 'cavidades', attrs: { text: { text: `${i}` } } }); } 
                nodoActual.set('ports', { groups: { 'cavidades': { position: orientacion, attrs: { circle: { r: 7, magnet: true, fill: '#f1c40f', stroke: '#d35400', strokeWidth: 2 }, text: { fill: '#ffffff', fontSize: 11, fontWeight: 'bold' } }, label: { position: cnf.portLabelPos } } }, items: pines }); 
            } cerrarModales(); 
        });

        $('#btn-save-splice').click(() => { if (nodoActual) { const nuevoNombre = $('#splice-name').val(); let d = nodoActual.get('bomData'); d.name = nuevoNombre; nodoActual.set('bomData', d); nodoActual.attr('label/text', nuevoNombre); } cerrarModales(); });
        
        $('#btn-save-loom').click(() => { 
                if (nodoActual) { 
                    const tipoRecubrimiento = $('#loom-tipo').val(); 
                    const qty = parseFloat($('#loom-qty').val()) || 1; 
                    
                    // 1. Clonamos los datos actuales para no perder el tamaño ni la orientación 
                    // que ya le diste al dibujar con el ratón.
                    let d = JSON.parse(JSON.stringify(nodoActual.get('bomData'))); 
                    
                    // 2. Solo actualizamos los campos que sí existen en tu nuevo modal
                    d.recubrimiento = tipoRecubrimiento; 
                    d.qty = qty; 
                    
                    nodoActual.set('bomData', d); 
                    
                    // 3. Actualizamos los colores dependiendo del tipo de tubo/manga
                    const colores = loomColors[tipoRecubrimiento]; 
                    nodoActual.attr('body/fill', colores.fill); 
                    nodoActual.attr('body/stroke', colores.stroke);
                    
                    // ELIMINAMOS el nodoActual.resize() que estaba aquí, 
                    // para que no destruya la forma que dibujaste en el lienzo.
                } 
                cerrarModales(); 
                actualizarEscaneoLooms(); // Forzamos el escáner para que actualice la etiqueta
            });

        $('#btn-save-tie').click(() => { 
            if (nodoActual) { 
                const tipoTie = $('#tie-tipo').val(); const desc = $('#tie-desc').val(); const qty = parseFloat($('#tie-qty').val()) || 1; const orientacion = $('#tie-orientacion').val(); 
                let d = nodoActual.get('bomData'); d.recubrimiento = tipoTie; d.description = desc; d.qty = qty; d.orientacion = orientacion; nodoActual.set('bomData', d); 
                const colores = tieColors[tipoTie]; nodoActual.attr('body/fill', colores.fill); nodoActual.attr('body/stroke', colores.stroke);
                if(orientacion === 'vertical') { nodoActual.attr('label/transform', 'rotate(-90)'); } else { nodoActual.attr('label/transform', 'rotate(0)');} 
            } cerrarModales(); 
        });

        graph.on('add', function(cell) { if (cell.isLink()) { cell.on('change:target', function(link) { if (link.get('target').id && link.get('source').id) { if (!link.get('bomData')) abrirModalCable(link); } }); } });
        paper.on('element:pointerdblclick', function(cellView) { 
            const tipo = cellView.model.get('bomData').type; 
            if (tipo === 'Connector') abrirModalConector(cellView.model); 
            if (tipo === 'Splice') abrirModalSplice(cellView.model); 
            if (tipo === 'Loom') abrirModalLoom(cellView.model); 
            if (tipo === 'BreakPoint') abrirModalBreak(cellView.model); 
            if (tipo === 'Tie') abrirModalTie(cellView.model); 
        });
        paper.on('link:pointerdblclick', function(linkView) { abrirModalCable(linkView.model); });

        $('#btn-zoom-in').click(() => { escalaActual += 0.1; paper.scale(escalaActual, escalaActual); $('#zoom-level').text(Math.round(escalaActual * 100) + '%'); });
        $('#btn-zoom-out').click(() => { if(escalaActual > 0.3) { escalaActual -= 0.1; paper.scale(escalaActual, escalaActual); $('#zoom-level').text(Math.round(escalaActual * 100) + '%'); } });
        $('#btn-clear-canvas').click(() => { if(confirm("¿Estás seguro?")) { graph.clear(); contSplices = 1; } });
        
        $('#btn-save-json').click(() => { const jsonStr = JSON.stringify(graph.toJSON(), null, 2); const blob = new Blob([jsonStr], { type: "application/json" }); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = "arnes_proyecto.json"; document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url); });
        $('#input-load-json').on('change', function(e) { const file = e.target.files[0]; if (!file) return; const reader = new FileReader(); reader.onload = function(event) { try { const jsonData = JSON.parse(event.target.result); graph.clear(); graph.fromJSON(jsonData); let maxSplice = 0; graph.getElements().forEach(el => { const d = el.get('bomData'); if(d && d.type === 'Splice' && d.name) { const match = d.name.match(/SP-(\d+)/); if(match && parseInt(match[1]) > maxSplice) { maxSplice = parseInt(match[1]); } } }); contSplices = maxSplice + 1; alert("Cargado."); } catch (error) { alert("Error."); } }; reader.readAsText(file); $(this).val(''); });

        // ==========================================
        // 7. CÁLCULO MATEMÁTICO DEL LOOM
        // ==========================================
        function obtenerDiametroPulgadas(mm) {
            if(mm === 0) return "0";
            const pulgadas = mm / 25.4;
            if(pulgadas <= 0.25) return '1/4"'; if(pulgadas <= 0.375) return '3/8"'; if(pulgadas <= 0.5) return '1/2"';
            if(pulgadas <= 0.625) return '5/8"'; if(pulgadas <= 0.75) return '3/4"'; if(pulgadas <= 1.0) return '1"'; if(pulgadas <= 1.25) return '1 1/4"';
            if(pulgadas <= 1.50) return '1 1/2"'; if(pulgadas <= 1.75) return '1 3/4"'; if(pulgadas <= 2.0) return '2"'; if(pulgadas <= 2.25) return '2 1/4"';
            return '2.5"'; 
        }

        function obtenerDiametroCable(calibre) {
            const cal = String(calibre).trim().toUpperCase();
            const tabla = {'24': 1.70, '22': 1.90, '20': 2.15, '18': 2.40, '16': 2.70, '14': 3.20, 
                            '12': 3.70, '10': 4.40, '8': 5.50, '6': 6.80, '4': 8.50, '2': 10.50, 
                            '1': 11.50, '1/0': 12.50, '0': 12.50, '2/0': 14.00, '00': 14.00, 
                            '3/0': 15.50, '000': 15.50, '4/0': 17.50, '0000': 17.50};
            if (tabla[cal]) return tabla[cal];
            let num = parseFloat(cal);
            if (!isNaN(num)) { if (num > 24) return 1.0; if (num >= 10 && num <= 100) return (2 * Math.sqrt(num / Math.PI)) + 2.0; }
            return 2.0; 
        }

        function calcularDiametroMazo(awgs) {
            if(awgs.length === 0) return 0;
            let areaTotal = 0; awgs.forEach(awg => { let diametro = obtenerDiametroCable(awg); let radio = diametro / 2; areaTotal += Math.PI * (radio * radio); });
            return (2 * Math.sqrt((areaTotal * 1.3) / Math.PI)); 
        }

        function actualizarEscaneoLooms() {
            const elements = graph.getElements(); const looms = elements.filter(el => el.get('bomData') && el.get('bomData').type === 'Loom');
            const links = graph.getLinks(); 

            looms.forEach(loom => {
                const bbox = loom.getBBox(); const rectLoom = new joint.g.Rect(bbox).inflate(15); 
                let awgs = []; 

                links.forEach(link => {
                    const view = link.findView(paper); if (!view) return;
                    const path = view.getConnection(); 
                    if (path) {
                        let cruzaLoom = false; const longitudCable = path.length();
                        for (let i = 0; i <= longitudCable; i += 5) {
                            const punto = path.pointAtLength(i);
                            if (rectLoom.containsPoint(punto)) { cruzaLoom = true; break; }
                        }
                        if (cruzaLoom) { const data = link.get('bomData'); if (data && data.gage) awgs.push(String(data.gage)); else awgs.push('18'); }
                    }
                });
                
                const numCables = awgs.length; 
                const d = loom.get('bomData') || {}; 
                const titulo = (d.description || 'MANGA').toUpperCase().split('(')[0].trim(); 

                // Esta es la longitud del dibujo en la pantalla (los 480 que veías)
                let longitudVisualMm = Math.round(d.orientacion === 'horizontal' ? bbox.width : bbox.height);

                // NUEVO: Tomamos la cantidad que pusiste en el modal. Si por alguna razón está vacío, usamos la visual.
                let longitudReal = d.qty || longitudVisualMm;

                if (numCables > 0) { 
                    const diametroMm = calcularDiametroMazo(awgs); 
                    const diametroPul = obtenerDiametroPulgadas(diametroMm);
                    console.log(diametroPul);
                    console.log(diametroMm);
                    
                    // MODIFICADO: Cambiamos longitudVisualMm por longitudReal en esta línea 👇
                    loom.attr('label/text', `${titulo}\n(${diametroPul})\n${numCables} Wires | L: ${longitudReal}mm`); 
                    
                    d.len = longitudVisualMm; // Mantenemos la longitud visual internamente para que no se deforme el dibujo
                    
                    let prefix = d.recubrimiento === 'corrugado' ? 'Corrugado' : (d.recubrimiento === 'manga' ? 'Manga' : 'Tubo PVC');
                    d.description = `${prefix} (${diametroPul})`; 
                    loom.set('bomData', d);
                } else { 
                    // MODIFICADO: También lo cambiamos aquí por si el tubo está vacío 👇
                    loom.attr('label/text', `${titulo}\n(Vacío)\nL: ${longitudReal}mm`); 
                    
                    d.len = longitudVisualMm; 
                    loom.set('bomData', d);
                }
            });
        }
        
        graph.on('change:position change:vertices change:size', _.debounce(actualizarEscaneoLooms, 300));
        graph.on('change:target change:source change:router', _.debounce(actualizarEscaneoLooms, 300));

        // ==========================================
        // 8. RUTEO ORTOGONAL
        // ==========================================
        function forzarAgrupacion(nodoCentral) {
            const bbox = nodoCentral.getBBox(); const centroX = bbox.x + (bbox.width / 2); const centroY = bbox.y + (bbox.height / 2); const puntoCentro = new joint.g.Point(centroX, centroY);
            const cablesAtrapados = [];
            graph.getLinks().forEach(link => { const view = link.findView(paper); if (view) { const rectLink = new joint.g.Rect(view.getBBox()).inflate(10); if (rectLink.distance(puntoCentro) <= 250) { cablesAtrapados.push(link); } } });
            
            if (cablesAtrapados.length === 0) { alert("No se detectaron cables cerca."); return; }
            const dataNodo = nodoCentral.get('bomData'); const isHorizontal = dataNodo.orientacion === 'horizontal' || dataNodo.type === 'BreakPoint';

            cablesAtrapados.forEach(link => {
                link.set('router', { name: 'normal' }); link.set('connector', { name: 'rounded', args: { radius: 15 } }); 
                link.set('locked', true); 
                const sp = link.getSourcePoint() || {x: 0, y:0}; const tp = link.getTargetPoint() || {x: 0, y:0};
                let v1 = { x: sp.x, y: sp.y }; let v4 = { x: tp.x, y: tp.y };
                const srcNode = graph.getCell(link.get('source').id); const tgtNode = graph.getCell(link.get('target').id); const stubLen = 40; 
                if(srcNode && srcNode.get('bomData')) { const ori = srcNode.get('bomData').orientacion; if(ori === 'right') v1.x += stubLen; if(ori === 'left') v1.x -= stubLen; if(ori === 'top') v1.y -= stubLen; if(ori === 'bottom') v1.y += stubLen; }
                if(tgtNode && tgtNode.get('bomData')) { const ori = tgtNode.get('bomData').orientacion; if(ori === 'right') v4.x += stubLen; if(ori === 'left') v4.x -= stubLen; if(ori === 'top') v4.y -= stubLen; if(ori === 'bottom') v4.y += stubLen; }
                let v2, v3;
                if (isHorizontal) { v2 = { x: v1.x, y: centroY }; v3 = { x: v4.x, y: centroY }; } else { v2 = { x: centroX, y: v1.y }; v3 = { x: centroX, y: v4.y }; }
                link.vertices([v1, v2, v3, v4]); 
            });
            actualizarEscaneoLooms(); cerrarModales();
        }

        function liberarAgrupacion(nodoCentral) {
            const bbox = nodoCentral.getBBox(); const puntoCentro = new joint.g.Point(bbox.x + (bbox.width/2), bbox.y + (bbox.height/2));
            graph.getLinks().forEach(link => { const view = link.findView(paper); if (view) { const rectLink = new joint.g.Rect(view.getBBox()).inflate(50); if (rectLink.distance(puntoCentro) <= 250) { link.set('locked', false); } } });
            alert("Cables DESBLOQUEADOS."); cerrarModales();
        }

        $('#btn-agrupar-loom, #btn-agrupar-break, #btn-agrupar-tie').click(() => { if (nodoActual) forzarAgrupacion(nodoActual); });
        $('.btn-desbloquear').click(() => { if (nodoActual) liberarAgrupacion(nodoActual); });

        // ==========================================
        // 9. REPORTES Y BOM
        // ==========================================
        function generarReporteRutas() {
            let i=0; const links = graph.getLinks(); let html = `<table style="font-size: 11px; white-space: nowrap;"><tr><th>ESTAMPADO (CIRCUITO)</th><th>CONS. RACK</th><th>TIPO DE CABLE</th><th>CALIBRE (AWG)</th><th>COLOR</th><th>LONGITUD (MM)</th><th>DESTINO 1 STRIP</th><th>DESTINO 1 TERMINAL</th><th>Herramienta 1</th><th>DESTINO 2 STRIP</th><th>DESTINO 2 TERMINAL</th><th>Herramienta 2</th><th>COLOR DE TINTA</th><th>QTY</th><th>DESTINO 1</th><th>DESTINO 2</th></tr>`;
            links.forEach(link => {
                i=i+1; const data = link.get('bomData'); if(!data) return;
                const srcNode = graph.getCell(link.get('source').id); const tgtNode = graph.getCell(link.get('target').id);
                if(srcNode && tgtNode) {
                    const srcData = srcNode.get('bomData'); const tgtData = tgtNode.get('bomData');
                    const rawSrcPort = link.get('source').port; const rawTgtPort = link.get('target').port;
                    let term1 = '-'; let term2 = '-'; let dest1 = ''; let dest2 = '';
                    if (srcData.type === 'Splice') { dest1 = srcData.name; } else { let numPin = rawSrcPort ? rawSrcPort.replace('via-', '') : ''; dest1 = `${srcData.description} - Pin ${numPin}`; if (srcData.terminals && srcData.terminals[rawSrcPort]) { term1 = srcData.terminals[rawSrcPort]; } }
                    if (tgtData.type === 'Splice') { dest2 = tgtData.name; } else { let numPin = rawTgtPort ? rawTgtPort.replace('via-', '') : ''; dest2 = `${tgtData.description} - Pin ${numPin}`; if (tgtData.terminals && tgtData.terminals[rawTgtPort]) { term2 = tgtData.terminals[rawTgtPort]; } }
                    html += `<tr><td><b>${data.circuito || 'S/N'}</b></td><td>${i}</td><td>${data.insulation}</td><td>${data.gage}</td><td>${data.color}</td><td>${data.length}</td><td>-</td><td><b>${term1}</b></td><td>-</td><td>-</td><td><b>${term2 || 'EMPALME'}</b></td><td>-</td><td>-</td><td>1</td><td>${dest1}</td><td>${dest2}</td></tr>`;
                }
            }); $('#tabla-rutas-container').html(html + '</table>');
        }

        function generarReporteBOM() {
            const elements = graph.getElements(); const links = graph.getLinks(); let componentes = {}; let terminalesTotales = {}; let cablesTotales = {};
            elements.forEach(el => {
                const data = el.get('bomData'); if(!data) return;
                if(data.type === 'Connector') { 
                    if(!componentes[data.housingPartNumber]) componentes[data.housingPartNumber] = { desc: data.description, qty: 0, unit: 'pzas' }; 
                    componentes[data.housingPartNumber].qty += 1; 
                    if (data.terminals) { for (let via in data.terminals) { let pnTerminal = data.terminals[via]; if(pnTerminal) { if(!terminalesTotales[pnTerminal]) terminalesTotales[pnTerminal] = 0; terminalesTotales[pnTerminal] += 1; } } } 
                } else if(data.type === 'Splice') { 
                    if(!componentes[data.name]) componentes[data.name] = { desc: 'Splice / Empalme', qty: 0, unit: 'pzas' }; componentes[data.name].qty += 1; 
                } else if(data.type === 'Loom') { 
                    if(!componentes[data.description]) componentes[data.description] = { desc: `Recubrimiento (${data.recubrimiento})`, qty: 0, unit: 'mm' }; // <--- Cambia 'mm' por 'pzas'
                    componentes[data.description].qty += parseFloat(data.qty || 1); 
                } else if(data.type === 'Tie') { 
                    if (data.recubrimiento === 'cinta') {
                        const descTape = 'Cinta Tape-25 (Puntos de Amarre / Quiebres)';
                        if(!componentes[descTape]) componentes[descTape] = { desc: descTape, qty: 0, unit: 'inches' }; 
                        componentes[descTape].qty += (data.qty * 3); 
                    } else {
                        if(!componentes[data.description]) componentes[data.description] = { desc: `Amarre (${data.recubrimiento})`, qty: 0, unit: 'pzas' }; componentes[data.description].qty += (data.qty || 1); 
                    }
                } else if(data.type === 'BreakPoint') { 
                    const descTape = 'Cinta Tape-25 (Puntos de Amarre / Quiebres)';
                    if(!componentes[descTape]) componentes[descTape] = { desc: descTape, qty: 0, unit: 'inches' }; 
                    componentes[descTape].qty += 6; 
                }
            });
            links.forEach(link => { const data = link.get('bomData'); if(!data) return; const llaveCable = `${data.gage} AWG ${data.insulation} ${data.color}`; if(!cablesTotales[llaveCable]) cablesTotales[llaveCable] = 0; cablesTotales[llaveCable] += data.length; });
            
            let html = `<h3>Conectores, Splices y Recubrimientos</h3><table><tr><th>Part Number / Identificador</th><th>Descripción / Tipo</th><th>Cantidad Requerida</th></tr>`; 
            for(let pn in componentes) html += `<tr><td>${pn}</td><td>${componentes[pn].desc}</td><td>${componentes[pn].qty} ${componentes[pn].unit}</td></tr>`; 
            html += `</table><br><h3>Terminales</h3><table><tr><th>Terminal Part Number</th><th>Cantidad Requerida</th></tr>`; 
            for(let termPn in terminalesTotales) html += `<tr><td>${termPn}</td><td>${terminalesTotales[termPn]} pzas</td></tr>`; 
            html += `</table><br><h3>Longitud Total de Cables</h3><table><tr><th>Especificación de Cable</th><th>Longitud Total (mm)</th></tr>`; 
            for(let cable in cablesTotales) html += `<tr><td>${cable}</td><td>${cablesTotales[cable]} mm</td></tr>`; 
            $('#tabla-bom-container').html(html + '</table>');
        }

        function generarReporteSplices() {
            let html = ''; const elements = graph.getElements(); let haySplices = false;
            elements.forEach(el => {
                const data = el.get('bomData');
                if(data && data.type === 'Splice') {
                    haySplices = true; const links = graph.getConnectedLinks(el); let conexiones = [];
                    links.forEach(link => {
                        const lData = link.get('bomData'); if(!lData) return;
                        const isSource = link.get('source').id === el.id; const otherId = isSource ? link.get('target').id : link.get('source').id; const otherPort = isSource ? link.get('target').port : link.get('source').port; const otherEl = graph.getCell(otherId);
                        if(otherEl) {
                            const oData = otherEl.get('bomData'); let destName = '';
                            if (oData.type === 'Splice') { destName = `Hacia ${oData.name}`; } else { let numPin = otherPort ? otherPort.replace('via-','') : ''; destName = `${oData.description} (Pin ${numPin})`; }
                            conexiones.push({ circuito: lData.circuito || 'S/N', cable: `${lData.gage} AWG ${lData.color}`, longitud: lData.length, destino: destName });
                        }
                    });
                    html += buildSpliceSVG(data.name, conexiones);
                }
            });
            if(!haySplices) { html = '<p style="color: #666;">No hay empalmes (Splices) en tu diseño actual.</p>'; } $('#tabla-splices-container').html(html);
        }

        function escapeHTML(str) { if (!str) return ""; return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;"); }

        function buildSpliceSVG(name, conexiones) {
            const groups = {};
            conexiones.forEach(con => { const match = con.destino.match(/^(.*?)(?:\s*\()/); const baseDest = match ? match[1].trim() : con.destino.trim(); if (!groups[baseDest]) groups[baseDest] = []; groups[baseDest].push(con); });
            const leftConns = []; const rightConns = []; const sortedGroups = Object.values(groups).sort((a, b) => b.length - a.length);
            if (sortedGroups.length === 1 && sortedGroups[0].length > 1) { const allConns = sortedGroups[0]; leftConns.push(allConns[0]); rightConns.push(...allConns.slice(1)); } else { sortedGroups.forEach((group, index) => { if (index === 0) { leftConns.push(...group); } else { rightConns.push(...group); } }); }

            const spacingY = 70; const boxWidth = 140; const boxHeight = 55; const lineLengthX = 160; const maxSideCount = Math.max(leftConns.length, rightConns.length); const h = Math.max(300, (maxSideCount * spacingY) + 100); const w = 800; const cx = w / 2; const cy = h / 2; const r = 25;
            let svgHtml = `<div class="splice-diagram" style="max-width: 100%; overflow-x: auto;"><h3 style="color:#c0392b; margin-top:0; text-align:center;">Empalme: ${escapeHTML(name)}</h3><svg viewBox="0 0 ${w} ${h}" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">`;

            const drawSide = (conns, isLeft) => {
                const count = conns.length; const startY = cy - ((count - 1) * spacingY) / 2;
                conns.forEach((con, index) => {
                    const currentY = startY + (index * spacingY); const endX = isLeft ? cx - lineLengthX : cx + lineLengthX;
                    svgHtml += `<line x1="${cx}" y1="${cy}" x2="${endX}" y2="${currentY}" stroke="#34495e" stroke-width="4"/>`;
                    const rectX = isLeft ? endX - boxWidth - 10 : endX + 10; const rectY = currentY - (boxHeight / 2);
                    svgHtml += `<rect x="${rectX}" y="${rectY}" width="${boxWidth}" height="${boxHeight}" fill="#ecf0f1" rx="4" ry="4" stroke="#bdc3c7" stroke-width="1"/>`;
                    const textCenterX = rectX + (boxWidth / 2);
                    svgHtml += `<text x="${textCenterX}" y="${rectY + 18}" font-family="sans-serif" font-size="10" fill="#2c3e50" font-weight="bold" text-anchor="middle">[${escapeHTML(con.circuito)}] ${escapeHTML(con.cable)}</text><text x="${textCenterX}" y="${rectY + 33}" font-family="sans-serif" font-size="9" fill="#d35400" font-weight="bold" text-anchor="middle">A: ${escapeHTML(con.destino)}</text><text x="${textCenterX}" y="${rectY + 46}" font-family="sans-serif" font-size="9" fill="#7f8c8d" text-anchor="middle">L: ${escapeHTML(con.longitud)} mm</text>`;
                });
            };
            if (conexiones.length > 0) { drawSide(leftConns, true); drawSide(rightConns, false); } else { svgHtml += `<text x="${cx}" y="${cy+60}" font-family="sans-serif" font-size="12" fill="#7f8c8d" text-anchor="middle">(Sin cables conectados)</text>`; }
            svgHtml += `<circle cx="${cx}" cy="${cy}" r="${r}" fill="#e74c3c" stroke="#c0392b" stroke-width="3"/><text x="${cx}" y="${cy+5}" font-family="sans-serif" font-size="14" fill="white" font-weight="bold" text-anchor="middle">SPL</text></svg></div>`;
            return svgHtml;
        }