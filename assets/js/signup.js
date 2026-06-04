const common = new Common();
const api = new Api();

$(document).on("click", "#signup-form button", async function() {
    let username = $("#signup-form #username").val();
    let password = $("#signup-form #password").val();
    let email = $("#signup-form #email").val();

    if (!username || !email || !password) {
        alert("Tutti i campi sono obbligatori");
        return;
    }

    try {
        const signup = await api.Signup(username, email, password);
        alert("Registrazione completata con successo");
        window.location.href = "/login";
    } catch (error) {
        alert("Errore durante la registrazione o utente già esistente");
    }
});