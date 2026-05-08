const api = new Api();
const common = new Common();

$(document).on("click", "#logout-button", function() {
    if (confirm("Vuoi effettuare il logout?")) {
        api.Logout(common.GetCookieValue("ttrack_login"), common.GetCookieValue("ttrack_user"));
    } else {
        return;
    }
});