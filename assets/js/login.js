const common = new Common();
const api = new Api();

$(document).on("click", "#login-form button", async function() {
    let username = $("#login-form #user").val();
    let password = $("#login-form #password").val();

    if (!username || !password) {
        alert("Username o Password non valorizzati");
        return;
    }

    try {
        const login = await api.Login(username, password, navigator.userAgent);
        common.SetLoginCookiesAndRedirect(login[0], login[1]);
    } catch (error) {
        alert("Credenziali errate");
    }
});