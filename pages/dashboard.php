<!DOCTYPE html>
<html>
    <head>
        <title>TTrack Dashboard</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/assets/js/common.js"></script>
        <script src="/assets/js/<?php echo basename(__FILE__, '.php'); ?>.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>
        <link rel="stylesheet" href="/assets/css/core.css">
        <link rel="stylesheet" href="/assets/css/<?php echo basename(__FILE__, '.php'); ?>.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    </head>
    <body>
        <section id="add-area">
            <div id="add-panel">
                <input id="add-date" type="date" value="<?php echo date('Y-m-d', time()) ?>">
                <span class="time-field"><input id="add-start-time" type="time" step="1"><span class="time-now" field="add-start-time"><i class="fa-regular fa-clock"></i></span></span>
                <span class="time-field"><input id="add-end-time" type="time" step="1"><span class="time-now" field="add-end-time"><i class="fa-regular fa-clock"></i></span></span>
                <button id="submit-time">Aggiungi</button>
            </div>
        </section>
        <section id="timetable-area">
            <span id="timetable-export"><i class="fa-solid fa-file-csv"></i></span>
            <table id="timetable-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th><span class="no-mobile">Orario di ingresso</span><span class="only-mobile">Ingresso</span></th>
                        <th><span class="no-mobile">Orario di uscita</span><span class="only-mobile">Uscita</span></th>
                    </tr>
                </thead>
                <!-- Dynamically compiled -->
                <tbody>
                </tbody>
            </table>
        </section>
        <modal>
            <div>
                <div id="modal-backdrop"></div>
                <span id="close-modal"><i class="fa-solid fa-xmark"></i></span>
                <div id="edit-panel">
                    <input id="edit-date" type="date" value="<?php echo date('Y-m-d', time()) ?>">
                    <span class="time-field"><input id="edit-start-time" type="time"><span class="time-now" field="edit-start-time"><i class="fa-regular fa-clock"></i></span></span>
                    <span class="time-field"><input id="edit-end-time" type="time"><span class="time-now" field="edit-end-time"><i class="fa-regular fa-clock"></i></span></span>
                    <button id="submit-edit-time">Modifica</button>
                </div>
            </div>
        </modal>
    </body>
</html>