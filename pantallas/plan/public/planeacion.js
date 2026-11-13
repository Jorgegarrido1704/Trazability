"use strict";

const rows = document.querySelector("#rows");
const tableContainer = document.querySelector("#table-container");
const message = document.querySelector("#message");
const dateLabel = document.querySelector("#date");
const countLabel = document.querySelector("#count");
const updated = document.querySelector("#updated");
const refresh = document.querySelector("#refresh");

const columns = [
    ["customer", "Customer", ""],
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

let consultaActiva;

function loadSchedule() {
    if (!consultaActiva) {
        refresh.disabled = true;
        consultaActiva = consultarPlaneacion().finally(() => {
            consultaActiva = null;
            refresh.disabled = false;
        });
    }
    return consultaActiva;
}

async function consultarPlaneacion() {
    let errorConsulta;
    try {
        const response = await fetch("./planeacion_hoy.php", {
            cache: "no-store",
            signal: AbortSignal.timeout(15000),
        });
        if (!response.ok) {
            errorConsulta = (await response.json().catch(() => ({}))).error;
            throw new Error("Consulta fallida");
        }
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
        message.textContent = errorConsulta || (tableContainer.hidden
            ? "No se pudo consultar la planeación. Usa Actualizar para reintentar."
            : "No se pudo actualizar la planeación. La tabla conserva los datos de la última consulta.");
        message.hidden = false;
    }
}

refresh.addEventListener("click", loadSchedule);
loadSchedule();
window.setInterval(loadSchedule, 60000);

const importForm = document.querySelector("#import-form");
const importFile = document.querySelector("#csv");
const importButton = document.querySelector("#import-button");
const importStatus = document.querySelector("#import-status");

function showImportStatus(text, isError = false) {
    importStatus.textContent = text;
    importStatus.hidden = !text;
    importStatus.classList.remove("error");
    if (isError) importStatus.classList.add("error");
}

async function importRequest(options) {
    const response = await fetch("./cargar_csv.php", { cache: "no-store", ...options });
    const result = await response.json().catch(() => ({}));
    if (!response.ok) {
        return { error: result.error || "No se pudo completar la solicitud de carga." };
    }
    return result;
}

async function enableImport() {
    try {
        const access = await importRequest({ signal: AbortSignal.timeout(10000) });
        if (typeof access.token !== "string") {
            showImportStatus(access.error || "No se pudo comprobar el permiso de carga.", true);
            return;
        }
        showImportStatus("");
        const password = document.querySelector("#import-password");
        document.querySelector("#import-password-field").hidden = !access.requiere_clave;
        password.disabled = !access.requiere_clave;
        password.required = Boolean(access.requiere_clave);
        importForm.hidden = false;
        importForm.addEventListener("submit", async event => {
            event.preventDefault();
            if (importButton.disabled || !importFile.files.length) return;

            const archivo = new FormData(importForm);
            importButton.disabled = true;
            importFile.disabled = true;
            showImportStatus("Importando planeación…");
            try {
                const result = await importRequest({
                    method: "POST",
                    headers: { "X-CVTS-CSRF": access.token },
                    body: archivo,
                });
                if (result.error) {
                    showImportStatus(result.error, true);
                    return;
                }
                if (!Number.isInteger(result.guardados) || !Number.isInteger(result.reemplazados)) {
                    throw new Error("Respuesta de carga inválida");
                }
                importForm.reset();
                showImportStatus(`Carga completada. Registros nuevos: ${result.guardados}. Registros reemplazados: ${result.reemplazados}.`);
                if (consultaActiva) await consultaActiva;
                await loadSchedule();
            } catch {
                showImportStatus("No se pudo confirmar la carga. Actualiza la tabla para comprobar el resultado.", true);
            } finally {
                importFile.disabled = false;
                importButton.disabled = false;
            }
        });
    } catch {
        showImportStatus("No se pudo comprobar el permiso de carga. Vuelve a abrir la pantalla.", true);
    }
}

enableImport();
