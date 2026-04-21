class Api {
    constructor() {
    }

    async GetTimes(username) { 
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: "/api/times/?user=" + username,
                success: function(msg) {
                    resolve(msg);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        resolve(xhr);
                    } else {
                        resolve(xhr);
                    }
                }
            });
        });
    }

    async GetCSV(username) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: "/api/csv/?user=" + username,
                success: function(msg) {
                    resolve(msg);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        resolve(xhr);
                    } else {
                        resolve(xhr);
                    }
                }
            });
        });
    }

    async Login(username, password) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "/api/login/",
                data: { username, password },
                success: function(msg) {
                    resolve(msg);
                },
                error: function(error) {
                    reject(error);
                }
            });
        });
    }

    async AddTime(date, startTime, endTime, user) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "/api/add-time/",
                data: {
                    "date": date,
                    "start": startTime,
                    "end": endTime,
                    "user": user
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(error) {
                    reject(error);
                }
            });
        });
    }

    async EditTime(id, date, startTime, endTime, user) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "/api/edit-time/",
                data: {
                    "id": id,
                    "date": date,
                    "start": startTime,
                    "end": endTime,
                    "user": user
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(error) {
                    reject(error);
                }
            });
        });
    }
}

class Common {
    constructor() {
    }

    GetCookieValue(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();

        return value;
    }

    SetLoginCookiesAndRedirect(userId, hash) {
        //30 days duration
        var date = new Date();
        date.setTime(date.getTime() + (30*24*60*60*1000));
        const expires = "; expires=" + date.toUTCString();
    
        document.cookie = "ttrack_login="+hash+expires+"; path=/";
        document.cookie = "ttrack_user="+userId+expires+"; path=/";
    
        document.location = "/";
    }
}