const common = new Common();
const api = new Api();

//#region Events
$(document).ready(RenderTimeTable());

$(document).on("click", "#submit-time", function() {
    let date = $("#add-date").val();
    let startTime = $("#add-start-time").val();
    let endTime = $("#add-end-time").val();
    let user = common.GetCookieValue("ttrack_user");

    if(!date || !startTime || !endTime || !user) {
        alert("Impossibile inviare i dati: campi incompleti");
        return;
    }

    api.AddTime(date, startTime, endTime, user)
        .then(function() {
            window.location.reload();
        })
        .catch(function(error) {
            console.error("Errore durante l'invio:", error);
            alert("Errore durante l'invio dei dati, Riprova.");
        });
});

$(document).on("click", ".time-now", function() {
    let currentTime = GetCurrentTimeString();
    let fieldToEdit = $(this).attr("field");
    $("#"+fieldToEdit).val(currentTime);
});

$(document).on("click", ".timetable--edit-time", function() {
    let id = $(this).attr("data-time");
    let timeColumns = $("tr[data-time="+id+"] td");
    let originalDate = $(timeColumns[0]).find("[data-date]").attr("data-date");
    let originalStartTime = $(timeColumns[1]).find("[data-time]").attr("data-time");
    let originalEndTime = $(timeColumns[2]).find("[data-time]").attr("data-time");

    $("modal").addClass("opened");

    $("modal #edit-date").val(originalDate);
    $("modal #edit-start-time").val(originalStartTime);
    $("modal #edit-end-time").val(originalEndTime);
    $("modal #submit-edit-time").attr("data-time", id);
});

$(document).on("click", "#close-modal", function() {
    $("modal").removeClass("opened");
    $("modal #edit-date").val("");
    $("modal #edit-start-time").val("");
    $("modal #edit-end-time").val("");
});

$(document).on("click", "#submit-edit-time", function() {
    let id = $(this).attr("data-time");
    let date = $("#edit-date").val();
    let startTime = $("#edit-start-time").val();
    let endTime = $("#edit-end-time").val();
    let user = common.GetCookieValue("ttrack_user");

    console.log("ID:", id);
    console.log("Date:", date);
    console.log("Start Time:", startTime);
    console.log("End Time:", endTime);
    console.log("User:", user);

    if(!id || id == "") {
        alert("Impossibile inviare i dati: inserimento non valido");
        return;
    } else {
        if(!date || !startTime || !endTime || !user) {
            alert("Impossibile inviare i dati: campi incompleti");
            return;
        }
    }

    api.EditTime(id, date, startTime, endTime, user)
        .then(function() {
            alert("Timbratura modificata");

            $("modal").removeClass("opened");
            $("modal #edit-date").val("");
            $("modal #edit-start-time").val("");
            $("modal #edit-end-time").val("");
            window.location.reload();
        })
        .catch(function(error) {
            console.error("Errore durante l'invio:", error);
            alert("Errore durante la modifica dei dati, Riprova.");
        });
    
});

$(document).on("click", "#timetable-export", function() {
    if (confirm("Vuoi davvero esportare tutti gli orari?")) {
        DownloadCSV();
    } else {
        return;
    }
});
//#endregion

//#region Functions
function GetCurrentTimeString() {
    let date = new Date();
    let hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
    let minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
    let seconds = date.getSeconds() < 10 ? "0" + date.getSeconds()  : date.getSeconds() ;

    return hours + ":" + minutes + ":" + seconds;
}

async function RenderTimeTable() {
    let times = await api.GetTimes(common.GetCookieValue("ttrack_user"));

    if(times.length < 1) {
        $("#timetable-export").addClass("hide");
        $("#timetable-table").addClass("hide");
    }

    let tableContent = "";

    times.forEach((singleTime) => {
        tableContent += RenderTimeTableRow(singleTime);
    });

    $("#timetable-table tbody").append(tableContent);
}

function RenderTimeTableRow(singleTime) {
    let html = "<tr data-time='" + singleTime.id + "'>" + RenderTimeTableColumn(singleTime.date, true) + RenderTimeTableColumn(singleTime.start_time) + RenderTimeTableColumn(singleTime.end_time) + RenderTimeTableColumnEdit(singleTime.id) + "</tr>";

    return html;
}

function RenderTimeTableColumn(timeColumn, isDate = false) {
    let html;
    
    if(isDate) {
        let dateString = timeColumn.split("-");
        let truncatedMonth = NumberToMonth(parseInt(dateString[1])).substring(0, 3);
        html = "<td><span class='date-container' data-date='" + timeColumn + "'><span>" + dateString[2]  + " " + truncatedMonth + "</span><span>" + dateString[0] + "</span></span></td>";
    } else {
        let timeString = timeColumn.split(":");
        html = "<td><span data-time='" + timeColumn + "'><span>" + timeString[0] + ":" + timeString[1] + "</span><span class='time-seconds'>:" + timeString[2] + " </span></span></td>";
    }

    return html;
}

function RenderTimeTableColumnEdit(timeId) {
    let html = '<td><span class="timetable--edit-time" data-time="' + timeId + '"><i class="fa-regular fa-pen-to-square"></i></span></td>';

    return html;
}

function NumberToMonth(monthNumber) {
    let monthNames = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];

    let monthString = monthNames[monthNumber - 1];
    
    return monthString;
}

async function DownloadCSV() {
    let csv = await api.GetCSV(common.GetCookieValue("ttrack_user"));

    let print = "data:text/csv;charset=utf-8,";

    let csvHeader = "Data,Orario inizio,Orario fine\n";

    print += csvHeader;

    csv.forEach((singleCSV) => {
        print += singleCSV.date + "," + singleCSV.start_time + "," + singleCSV.end_time + "\n";
    });

    let today = new Date().toISOString().slice(0, 10)

    var encodedUri = encodeURI(print);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "orari_" + today + ".csv");
    
    document.body.appendChild(link); //For compatibility with Firefox🦊

    link.click();
}
//#endregion