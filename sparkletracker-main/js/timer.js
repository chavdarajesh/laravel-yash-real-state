var Timer = require("easytimer.js").Timer;
const {
    shell
} = require('electron');
//const ioHook = require("iohook");


var timerInstance = new Timer();
var timer = new Timer();

console.log('tttttttttttttttttttttimer js',store.get());
$("#user-name").text(store.get("Username"));
// $('#tracker_memo_p').text(store.get("trackerMemo"));
// $('#tracker_memo_text').text(store.get("trackerMemo"));
// $('.todayTotalTime').text(store.get("todayTotalTime"));
// $('.weekTotalTime').text(store.get("weekTotalTime"));
// $("#last_sctime").text(store.get("last_sctime"));
// $("#imageid").attr('src', store.get("imageUrl"));

$(document).on("change", "#timeSwitch", function () {
    if ($(this).prop("checked") == true) {
        task.start();
        timer.reset();
        $(".total_current_session").addClass('active_timer');
        $(".tracker_status").show();
    } else if ($(this).prop("checked") == false) {
        task.stop();
        timer.stop();
        $(".total_current_session").removeClass('active_timer');
        $('.total_min').text('00');
        $('.total_hrs').text('0');
        $(".tracker_status").hide();
    }
});

// $(document).on('click', '.count-text-2', function () {
//     $('.no_edit').addClass('d-none');
//     $('#tracker_memo_text').removeClass('d-none');
//     $('.yes_edit').removeClass('d-none');
// });

// $(document).on('click', '#save_memo', function () {
//     $('.tracker_memo_text').addClass('d-none');
//     $('.yes_edit').addClass('d-none');
//     $('.-pencil-icon').addClass('d-none');
//     $('.no_edit').removeClass('d-none');

//     let tracker_memo_text = $('#tracker_memo_text').val();
//     let truncated_text = tracker_memo_text.length > 30 
//                          ? tracker_memo_text.substring(0, 30) + '...' 
//                          : tracker_memo_text;
    

//     $('#tracker_memo_p').text(truncated_text);
// });

$(document).on('click', '#imageid', function () {
    shell.openExternal($(this).attr('src'));
});

$(document).on('click', '#forgot-password', function () {
    shell.openExternal($(this).attr('data-href'));
});
$(document).on('click', '#time-tracker-div', function () {
    shell.openExternal($(this).attr('data-href'));
});

var d = new Date();
var weekday = new Array(7);
weekday[0] = "Sun";
weekday[1] = "Mon";
weekday[2] = "Tue";
weekday[3] = "Wed";
weekday[4] = "Thu";
weekday[5] = "Fri";
weekday[6] = "Sat";
var n = weekday[d.getUTCDay()];
$('.tracker_day_name').text('Today (' + n + ' UTC)');
timer.addEventListener("secondsUpdated", function (e) {
    time_count = timer.getTimeValues().toString(['minutes']);
    $('.total_min').text(time_count);
    $('.total_hrs').text(timer.getTimeValues().hours);
});
$(document).on("click", "#settings-menu-bottom", function () {
    $(".tracker-drop").toggle();
});