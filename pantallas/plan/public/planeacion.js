"use strict";

const rows = document.querySelector("#rows");
const tableContainer = document.querySelector("#table-container");
const message = document.querySelector("#message");
const dateLabel = document.querySelector("#date");
const countLabel = document.querySelector("#count");
const updated = document.querySelector("#updated");
const refresh = document.querySelector("#refresh");

const columns = [
    ["np", "Número de parte", "number"],
    ["wo", "WO", "number"],
    ["qty_wo", "Cantidad", "center number"],
    ["corte", "L", "center"],
    ["ensa", "E", "center"],
    ["loom", "L", "center"],
    ["cal", "C", "center"],
    ["emb", "E", "center"],
    ["fecha", "Fecha requerida", "number"],
    ["comentarios", "Comentarios", "comment"],
];

const longDate = new Intl.DateTimeFormat("es-MX", { dateStyle: "full", timeZone: "UTC" });
const shortDate = new Intl.DateTimeFormat("es-MX", { dateStyle: "short", timeZone: "UTC" });
const formatDate = (value, formatter) => formatter.format(new Date(value + "T00:00:00Z"));

function renderRows(records) {
    const fragment = document.createDocumentFragment();
    for (const record of records) {
        const row = document.createElement("tr");
        row.setAttribute("role", "row");
        for (const [field, label, classes] of columns) {
            const cell = document.createElement("td");
            cell.setAttribute("role", "cell");
            cell.dataset.label = label;
            cell.className = classes;
            const value = field === "fecha" && record[field]
                ? formatDate(record[field], shortDate)
                : String(record[field] ?? "");
            cell.textContent = value.trim() ? value : "—";
            if (!value.trim()) cell.classList.add("empty");
            row.append(cell);
        }
        fragment.append(row);
    }
    rows.replaceChildren(fragment);
}

async function loadSchedule() {
    if (refresh.disabled) return;
    refresh.disabled = true;
    try {
        const response = await fetch("./planeacion_hoy.php", {
            cache: "no-store",
            signal: AbortSignal.timeout(15000),
        });
        if (!response.ok) throw new Error("Consulta fallida");
        const { fecha, zona_horaria, consultado_en, registros } = await response.json();
        const dateText = formatDate(fecha, longDate);
        const queryTime = new Intl.DateTimeFormat("es-MX", {
            timeStyle: "short",
            timeZone: zona_horaria,
        }).format(new Date(consultado_en));
        renderRows(registros);

        dateLabel.textContent = dateText;
        countLabel.textContent = registros.length === 1 ? "1 registro" : `${registros.length} registros`;
        countLabel.hidden = false;
        tableContainer.hidden = registros.length === 0;
        message.classList.remove("error");
        message.textContent = registros.length ? "" : "No hay órdenes cargadas hoy.";
        message.hidden = registros.length > 0;
        updated.textContent = `Última actualización ${queryTime} · hora de la planta`;
        updated.hidden = false;
    } catch {
        message.classList.add("error");
        message.textContent = tableContainer.hidden
            ? "No se pudo consultar la planeación. Usa Actualizar para reintentar."
            : "No se pudo actualizar la planeación. La tabla conserva los datos de la última consulta.";
        message.hidden = false;
    } finally {
        refresh.disabled = false;
    }
}

refresh.addEventListener("click", loadSchedule);
loadSchedule();
window.setInterval(loadSchedule, 60000);
