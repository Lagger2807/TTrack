const api = new Api();
const common = new Common();

function Logout() {
    if (confirm("Vuoi effettuare il logout?")) {
        api.Logout(common.GetCookieValue("ttrack_login"), common.GetCookieValue("ttrack_user"));
    } else {
        return;
    }
}

function LogoutAll() {
    if (confirm("Vuoi effettuare il logout da tutti i dispositivi? Questa azione non può essere annullata.")) {
        api.LogoutAll(common.GetCookieValue("ttrack_user"));
    } else {
        return;
    }
}

function EditName() {
    const newName = prompt("Inserisci il nuovo nome:");
    if (newName) {
        api.UpdateName(common.GetCookieValue("ttrack_login"), common.GetCookieValue("ttrack_user"), newName)
            .then(response => {
                alert("Nome aggiornato con successo!");
                location.reload();
            })
            .catch(error => {
                console.error("Errore nella richiesta API:", error);
                alert("Si è verificato un errore durante la richiesta.");
            });
    }
}