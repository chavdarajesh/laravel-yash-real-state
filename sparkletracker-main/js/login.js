

$('#login-form').submit(function (e) {
    e.preventDefault();
    console.log('-----------------form submit');
    showloader()
    var email = password = '';
    email = $("#email").val() ? $("#email").val() : '';
    password = $("#password").val() ? $("#password").val() : '';

    if ($('#remember_me').prop("checked") == true) {
        store.set('mainScreen', 1);
    } else {
        store.set('mainScreen', 0);
    }
    console.log('txtPwd', email, password);

    var options = {
        method: "POST",
        url: "http://localhost/laravel/time-tracker/api/login",
        formData: {
            email: email,
            password: password,
        }
    };

    request(options, function (error, response) {
        hideloader()
        if (error) {
            showToast(error.message, 'error')
            throw new Error(error);
        } else {
            console.log('response login ', response.body);
            var obj = JSON.parse(response.body);
            console.log('obj', obj);
            if (obj.status == 200) {
                showToast(obj.message, 'success')
                const data = obj.data;
                store.set("Username", data.username);
                store.set("api_token", data.api_token);
                store.set("user_logged_in", true);
                store.set("user_logged_nsg", obj.message);
                ipc.sendSync("entry-accepted", "ping");
            } else {
                showToast(obj.message, 'error')
            }
        }
    });
});


$(document).on("click", "#tracker_logout", function () {
    logout()
});
// $(document).on("click", "#tracker_feedback", function () {
//     ipc.sendSync("entry-accepted", "feedback");
// });
$(document).on("click", "#btn_cancel", function () {
    ipc.sendSync("entry-accepted", "feedback-cancel");
});

function logout() {
    if (task) {
        task.stop();
        timer.stop();
        store.delete("Username");
        store.delete("api_token");
        store.delete("imageUrl");
        store.delete("last_sctime");
        store.delete("trackerMemo");
        store.delete("todayTotalTime");
        store.delete("weekTotalTime");
        store.delete('mainScreen');
        store.delete('user_logged_in');
        store.delete('user_logged_nsg');
        ipc.sendSync("entry-accepted", "logout");
    }
}