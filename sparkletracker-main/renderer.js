let $ = require("jquery");
var cron = require("node-cron");
var CronJob = require("cron").CronJob;
const request = require("request");
const screenshot = require("screenshot-desktop");
const ipc = require("electron").ipcRenderer;
var fs = require("fs");
const ioHook = require("iohook");
const CronTime = require("cron").CronTime;
const path = require("path");
const os = require("os");
const moment = require("moment");

var keyCount = 0;
var clickCount = 0;
var minuteKeyCount = 0;
var minuteClickCount = 0;
var log = 0;
var queue = {};
var timeTrackerMinData = [];


$(document).on("change", "#timeSwitch", function () {
    if ($(this).prop("checked") == true) {
        showloader();
        ioHook.on("keydown", (event) => {
            keyCount = keyCount + 1;
            minuteKeyCount = minuteKeyCount + 1;
        });
        ioHook.on("mouseclick", (event) => {
            clickCount = clickCount + 1;
            minuteClickCount = minuteClickCount + 1;
        });

        ioHook.start();

        var currentMinute = new Date().getMinutes();

        switch (true) {
            case currentMinute <= 10:
                new_cron_time = between(11, 20) + " * * * *";
                break;
            case currentMinute <= 20:
                new_cron_time = between(21, 30) + " * * * *";
                break;
            case currentMinute <= 30:
                new_cron_time = between(31, 40) + " * * * *";
                break;
            case currentMinute <= 40:
                new_cron_time = between(41, 50) + " * * * *";
                break;
            case currentMinute <= 50:
                new_cron_time = between(51, 59) + " * * * *";
                break;
            case currentMinute <= 60:
                new_cron_time = between(0, 10) + " * * * *";
                break;
            default:
                break;
        }
        if (
            typeof store.get("last_sctime") != "undefined" &&
            store.get("last_sctime") !== null &&
            store.get("last_sctime") !== ""
        ) {
            if (
                store.get("last_sctime").match(/\d+/)[0] > 10 ||
                store.get("last_sctime").indexOf("hour") != -1 ||
                store.get("last_sctime").indexOf("days") != -1
            ) {
                new_cron_time = currentMinute + 3 + " * * * *";
            }
        } else {
            new_cron_time = currentMinute + 3 + " * * * *";
        }
        if (currentMinute >= 56) {
            new_cron_time = "3 * * * *";
        }
        // new_cron_time = '13 * * * *';
        console.log("new_cron_time 222222 => ", new_cron_time);

        task.stop();
        task.setTime(new CronTime(new_cron_time));
        task.start();
        eventCron.stop();
        eventCron.start();

        request.get(
            {
                url: "http://localhost/laravel/time-tracker/api/check-in",
                headers: {
                    Authorization: `Bearer ${store.get("api_token")}`,
                },
            },
            function callback(err, response, body) {
                hideloader();
                if (err) {
                    showToast(err, "error");
                    return console.error("Failed to upload:", err);
                } else {
                    console.log("response check in ", response.body);
                    var obj = JSON.parse(response.body);
                    if (obj.status == 200) {
                        showToast(obj.message, "success");
                    } else if (obj.status == 401) {
                        showToast(obj.message, "error");
                        logout();
                    } else {
                        showToast(obj.message, "error");
                    }
                }
            }
        );
    } else {
        showloader();
        ioHook.stop();
        task.stop();
        // log_api.stop();
        eventCron.stop();

        request.get(
            {
                url: "http://localhost/laravel/time-tracker/api/check-out",
                headers: {
                    Authorization: `Bearer ${store.get("api_token")}`,
                },
            },
            function callback(err, response, body) {
                hideloader();
                if (err) {
                    showToast(err, "error");
                    return console.error("Failed to upload:", err);
                } else {
                    console.log("response check out ", response.body);
                    var obj = JSON.parse(response.body);
                    if (obj.status == 200) {
                        showToast(obj.message, "success");
                    } else if (obj.status == 401) {
                        showToast(obj.message, "error");
                        logout();
                    } else {
                        showToast(obj.message, "error");
                    }
                }
            }
        );
    }
});

$(function () {
    var $offline = $(".offline");
    var isOffline = false;
    var loginBtn = $('#btn_login')

    Offline.on("confirmed-down", function () {
        console.log('oflline true ');
        $offline.fadeIn();
        $(".offline-ui-down").show();
        isOffline = true;
        if(loginBtn){
            loginBtn.prop('disabled', true);
        }
    });

    console.log('===-=-=-=-=-=-=-=-=-', store.get('offlineTimeTrakerData'));
    Offline.on("confirmed-up", function () {
        console.log('oflline false');
        $offline.fadeOut();
        console.log("------------------ 222222222222222 =>", isOffline);
        if (isOffline == true) {
            console.log('calling saveOfflineTimeTrackerData after ofline');
            isOffline = false;
            saveOfflineTimeTrackerData();
        }
        $(".offline-ui-up").hide();
        if(loginBtn){
            loginBtn.prop('disabled', false);
        }
    });



    Offline.options = {
        // Should we check the connection status immediatly on page load.
        checkOnLoad: true,
        // Should we monitor AJAX requests to help decide if we have a connection.
        interceptRequests: true,
        // Should we automatically retest periodically when the connection is down (set to false to disable).
        reconnect: {
            // How many seconds should we wait before rechecking.
            initialDelay: 1,
            // How long should we wait between retries.
            delay: 1,
        },
        checks: {
            xhr: {
                url: "http://localhost/laravel/time-tracker/api/check-offline",
            },
        },
        // Should we store and attempt to remake requests which fail while the connection is down.
        requests: true,
    };

    Offline.check();

    setInterval(function () {
        Offline.check();
    }, 20000);

    setTimeout(function () {
        console.log('Offline.state', Offline.state);
        if (Offline.state == 'up') {
            console.log('uploding offline data on load');
            saveOfflineTimeTrackerData();
            if(loginBtn){
                loginBtn.prop('disabled', false);
            }
        }else{
            if(loginBtn){
                loginBtn.prop('disabled', true);
            }
        }
    }, 100);


});

// var log_api = new CronJob(
//     "*/10 * * * *",
//     function () {
//         if (task.running == true) {
//             console.log('  "*/10 * * * *"');
//             request.post({
//                 url: "http://localhost/laravel/time-tracker/api/get-data",
//                 headers: {
//                     Authorization: `Bearer ${store.get("api_token")}`,
//                 },
//             },
//                 function callback(err, response, body) {
//                     if (err) {
//                         return console.error("Failed to upload:", err);
//                     }
//                     var obj = JSON.parse(response.body);
//                     console.log('obj   "*/10 * * * *"',obj);
//                 });
//         }
//     }
// );

function between(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

var currentMinute = new Date().getMinutes();

switch (true) {
    case currentMinute <= 10:
        new_cron_time = between(11, 20) + " * * * *";
        break;
    case currentMinute <= 20:
        new_cron_time = between(21, 30) + " * * * *";
        break;
    case currentMinute <= 30:
        new_cron_time = between(31, 40) + " * * * *";
        break;
    case currentMinute <= 40:
        new_cron_time = between(41, 50) + " * * * *";
        break;
    case currentMinute <= 50:
        new_cron_time = between(51, 59) + " * * * *";
        break;
    case currentMinute <= 59:
        new_cron_time = between(0, 10) + " * * * *";
        break;
    default:
        break;
}

if (
    typeof store.get("last_sctime") != "undefined" &&
    store.get("last_sctime") !== null &&
    store.get("last_sctime") !== ""
) {
    if (
        store.get("last_sctime").match(/\d+/)[0] > 10 ||
        store.get("last_sctime").indexOf("hour") != -1 ||
        store.get("last_sctime").indexOf("days") != -1
    ) {
        new_cron_time = currentMinute + 3 + " * * * *";
    }
} else {
    new_cron_time = currentMinute + 3 + " * * * *";
}
if (currentMinute >= 56) {
    new_cron_time = "3 * * * *";
}

var eventCron = cron.schedule("*/1 * * * *", function () {
    current_datetime = new Date().toUTCString();
    var eventData = {
        time: current_datetime,
        keyboard_events: minuteKeyCount,
        mouse_events : minuteClickCount,
    };
    timeTrackerMinData.push(eventData);
    minuteKeyCount = minuteClickCount = 0;
});

console.log("new_cron_time 22 => ", new_cron_time);

var task = new CronJob(
    new_cron_time,
    function () {
        var currentdate = new Date();
        var datetime =
            currentdate.getFullYear() +
            "_" +
            (currentdate.getMonth() + 1) +
            "_" +
            currentdate.getDate() +
            "_" +
            currentdate.getHours() +
            "_" +
            currentdate.getMinutes() +
            "_" +
            currentdate.getSeconds();
        datetime = datetime.toLocaleString("en-US", {
            hour12: false,
        });
        screenshot({
            filename: path.join(os.tmpdir(), datetime + ".png"),
        }).then((imgPath) => {
            setTimeout(function () {
                showloader();

                const currentYear = moment().year();
                console.log(currentYear);

                const dayOfYear = moment().dayOfYear();
                console.log(dayOfYear);

                const weekOfYear = moment().week();
                console.log(weekOfYear);

                const capturedAt = moment().format("YYYY-MM-DD HH:mm:ss");
                const capturedAtFormatted = moment().format(
                    "DD-MM-YYYY HH:mm:ss"
                );

                var options = {
                    method: "POST",
                    url: "http://localhost/laravel/time-tracker/api/save-time-tracker-data",
                    headers: {
                        Authorization: `Bearer ${store.get("api_token")}`,
                    },
                    formData: {
                        keyboard_events: keyCount,
                        mouse_events: clickCount,
                        timeFrameEvent: JSON.stringify(timeTrackerMinData),
                        screenshot: fs.createReadStream(imgPath),
                        year: currentYear,
                        year_day: dayOfYear,
                        week_no: weekOfYear,
                        captured_at: capturedAt,
                        captured_at_formatted: capturedAtFormatted,
                        datetime: datetime
                    },
                };
                queue[datetime] = options;

                console.log("queue", queue);
                if (Offline.state == "up") {
                    console.log('online');
                    request(options, function (error, response) {
                        hideloader();

                        if (error) {
                            showToast(error, "error");
                            request(options, function (error, response) {
                                $("#last_sctime").text("0 Min ago");
                                delete queue[datetime];
                                minuteKeyCount = minuteClickCount = 0;
                                timeTrackerMinData = [];
                                if (
                                    fs.existsSync(
                                        path.join(
                                            os.tmpdir(),
                                            datetime + ".png"
                                        )
                                    )
                                ) {
                                    fs.unlink(
                                        path.join(
                                            os.tmpdir(),
                                            datetime + ".png"
                                        ),
                                        function (err) { }
                                    );
                                }
                            });
                        } else {
                            console.log(
                                "response save-time-tracker-data ",
                                response.body
                            );
                            try {
                                var obj = JSON.parse(response.body);
                                if (obj.status == 200) {
                                    showToast(obj.message, "success");
                                    const data = obj.data;
                                    $("#imageid").attr("src", data.screenshot);
                                    $("#last_sctime").text(data.last_sctime);
                                    $("#trackerMemo").html(data.trackerMemo);
                                    $(".todayTotalTime").text(
                                        data.todayTotalTime
                                    );
                                    $(".weekTotalTime").text(
                                        data.weekTotalTime
                                    );
                                    delete queue[datetime];
                                    minuteKeyCount = minuteClickCount = 0;
                                    timeTrackerMinData = [];
                                    if (
                                        fs.existsSync(
                                            path.join(
                                                os.tmpdir(),
                                                datetime + ".png"
                                            )
                                        )
                                    ) {
                                        fs.unlink(
                                            path.join(
                                                os.tmpdir(),
                                                datetime + ".png"
                                            ),
                                            function (err) { }
                                        );
                                    }
                                } else if (obj.status == 401) {
                                    showToast(obj.message, "error");
                                    logout();
                                } else {
                                    showToast(obj.message, "error");
                                }
                            } catch (err) {
                                request(options, function (error, response) {
                                    showToast(obj.message, "success");

                                    if (obj.status == 200) {
                                        showToast(obj.message, "success");
                                        const data = obj.data;
                                        $("#imageid").attr("src", data.screenshot);
                                        $("#last_sctime").text(data.last_sctime);
                                        $("#trackerMemo").html(data.trackerMemo);
                                        $(".todayTotalTime").text(
                                            data.todayTotalTime
                                        );
                                        $(".weekTotalTime").text(
                                            data.weekTotalTime
                                        );
                                        delete queue[datetime];
                                        minuteKeyCount = minuteClickCount = 0;
                                        timeTrackerMinData = [];
                                        if (
                                            fs.existsSync(
                                                path.join(
                                                    os.tmpdir(),
                                                    datetime + ".png"
                                                )
                                            )
                                        ) {
                                            fs.unlink(
                                                path.join(
                                                    os.tmpdir(),
                                                    datetime + ".png"
                                                ),
                                                function (err) { }
                                            );
                                        }
                                    } else if (obj.status == 401) {
                                        showToast(obj.message, "error");
                                        logout();
                                    } else {
                                        showToast(obj.message, "error");
                                    }
                                });
                            }
                        }
                    });
                } else {
                    console.log('offline');

                    hideloader();
                    const data = {
                        keyboard_events: keyCount,
                        mouse_events: clickCount,
                        timeFrameEvent: JSON.stringify(timeTrackerMinData),
                        screenshot: fs.createReadStream(imgPath),
                        year: currentYear,
                        year_day: dayOfYear,
                        week_no: weekOfYear,
                        captured_at: capturedAt,
                        captured_at_formatted: capturedAtFormatted,
                        datetime: datetime
                    };

                    $("#last_sctime").text(data.captured_at_formatted);
                    delete queue[datetime];
                    minuteKeyCount = minuteClickCount = 0;
                    timeTrackerMinData = [];

                    let offlineTimeTrakerDataPush = store.get('offlineTimeTrakerData') || [];
                    if (!Array.isArray(offlineTimeTrakerDataPush)) {
                        offlineTimeTrakerDataPush = [];
                    }
                    offlineTimeTrakerDataPush.push(data);
                    store.set('offlineTimeTrakerData', offlineTimeTrakerDataPush);
                    console.log('000000', store.get('offlineTimeTrakerData'));

                }
                changeCrontime();
            }, 1000);
        });
    },
    {
        scheduled: true,
    }
);

// cron.schedule(
//     "*/30 * * * * *",
//     () => {
//         console.log("*/30 * * * * *", 'log event');
//         if (Offline.state == 'up') {
//             request.get({
//                 url: "http://localhost/laravel/time-tracker/api/get-data",
//                 headers: {
//                     Authorization: `Bearer ${store.get("api_token")}`,
//                 },
//             },
//                 function callback(err, response, body) {
//                     if (err) {
//                         return console.error("Failed to upload:", err);
//                     }
//                     console.log('-------------- */30 * * * * *');
//                     var obj = JSON.parse(response.body);
//                     const data = obj.data;
//                     $("#imageid").attr('src', data.imageUrl);
//                     $("#last_sctime").text(data.last_sctime);
//                     $('.todayTotalTime').text(data.todayTotalTime);
//                     $('.weekTotalTime').text(data.weekTotalTime);
//                     store.set("imageUrl", data.imageUrl);
//                     store.set("last_sctime", data.last_sctime);
//                 }
//             );
//             for (var key in queue) {
//                 if (queue.hasOwnProperty(key)) {
//                     request(queue[key], function (error, response) {
//                         try {
//                             delete queue[key];
//                             if (fs.existsSync(path.join(os.tmpdir(), key + ".png"))) {
//                                 fs.unlink(path.join(os.tmpdir(), key + ".png"), function (err) {
//                                 });
//                             }
//                         } catch (err) {
//                             // console.log(err);
//                         }
//                     });
//                 }
//             }
//         }
//     }
// );

$(document).on("click", "#btn_feedback", function () {
    var feedbackData = {
        eventName: "trackerFeeback",
        api_token: store.get("api_token"),
        feedbackMessage: $("#message").val(),
    };
    if (Offline.state == "up") {
        request.post(
            {
                url: "http://localhost/laravel/time-tracker/api/",
                headers: {
                    Cookie: "ci_session=1v8idirq42cm3e7he8r54jr26gcf8l6k",
                },
                formData: feedbackData,
            },
            function callback(err, response, body) {
                if (err) {
                    return console.error("Failed to upload:", err);
                } else {
                    var obj = JSON.parse(response.body);
                    if (obj.status == "success") {
                        $("#report_message")
                            .show()
                            .html("Your feedback successfully submitted.!")
                            .css("color", "green");
                        setTimeout(function () {
                            ipc.sendSync("entry-accepted", "feedback-cancel");
                        }, 3000);
                    } else {
                        $("#report_message")
                            .show()
                            .html("Something went wrong, please try again letter.!")
                            .css("color", "red");
                    }
                }
            }
        );
    }
});

function changeCrontime() {
    keyCount = 0;
    clickCount = 0;
    ioHook.stop();
    ioHook.start();
    currentMinute = new Date().getMinutes();
    switch (true) {
        case currentMinute <= 10:
            new_cron_time = between(11, 20) + " * * * *";
            break;
        case currentMinute <= 20:
            new_cron_time = between(21, 30) + " * * * *";
            break;
        case currentMinute <= 30:
            new_cron_time = between(31, 40) + " * * * *";
            break;
        case currentMinute <= 40:
            new_cron_time = between(41, 50) + " * * * *";
            break;
        case currentMinute <= 50:
            new_cron_time = between(51, 59) + " * * * *";
            break;
        case currentMinute <= 60:
            new_cron_time = between(0, 10) + " * * * *";
            break;
        default:
            break;
    }
    console.log("new_cron_time 33 =>", new_cron_time);
    task.stop();
    task.setTime(new CronTime(new_cron_time));
    task.start();
    eventCron.stop();
    eventCron.start();
}

function saveOfflineTimeTrackerData() {
    var offlineTimeTrakerDataSend = []
    offlineTimeTrakerDataSend = store.get('offlineTimeTrakerData')
    console.log('offlineTimeTrakerDataSend', offlineTimeTrakerDataSend);
    if (offlineTimeTrakerDataSend && offlineTimeTrakerDataSend.length > 0) {

        request.post({
            url: "http://localhost/laravel/time-tracker/api/save-offline-time-tracker-data",
            headers: {
                Authorization: `Bearer ${store.get("api_token")}`,
                'Content-Type': 'application/json' // Ensure the correct content type
            },
            body: JSON.stringify({ offlineTimeTrakerDataSend }) // Send data as JSON string
        },
            function callback(err, response, body) {
                if (err) {
                    return console.error("Failed to upload:", err);
                }
                console.log("Response offline save", response);
                var obj = JSON.parse(body);
                if (obj.status == 200) {
                    showToast(obj.message, "success");
                    const data = obj.data;

                    $("#imageid").attr("src", data.screenshot);
                    $("#last_sctime").text(data.last_sctime);
                    $(".todayTotalTime").text(data.todayTotalTime);
                    $(".weekTotalTime").text(data.weekTotalTime);
                    $("#trackerMemo").html(data.trackerMemo);

                    store.set('offlineTimeTrakerData', [])

                    offlineTimeTrakerDataSend.forEach(element => {
                        if (fs.existsSync(path.join(os.tmpdir(), element.datetime + ".png"))) {
                            fs.unlink(
                                path.join(os.tmpdir(), element.datetime + ".png"),
                                function (err) { }
                            );
                        }
                    });

                } else if (obj.status == 401) {
                    showToast(obj.message, "error");
                    logout();
                } else {
                    showToast(obj.message, "error");
                }

            });
    }

}
