$(function () {
    if (store.get('user_logged_in') && store.get('user_logged_nsg')) {
        showToast(store.get('user_logged_nsg'), 'success')
        store.delete("user_logged_in");
        store.delete("user_logged_nsg");
    }

})


function getTimeTrackerData() {
    console.log('getTimeTrackerData');
    showloader()
    request.get({
        url: "http://localhost/laravel/time-tracker/api/get-data",
        headers: {
            Authorization: `Bearer ${store.get("api_token")}`,
        },
    },
        function callback(err, response, body) {
            hideloader()
            if (err) {
                showToast(err, 'error')
            } else {
                console.log('response get data ',response.body);
                var obj = JSON.parse(response.body);
                if (obj.status == 200) {
                    showToast(obj.message, 'success')
                    const data = obj.data;
                    $("#imageid").attr('src', data.imageUrl);
                    $("#last_sctime").text(data.last_sctime);
                    $("#trackerMemo").html(data.trackerMemo);
                    $('.todayTotalTime').text(data.todayTotalTime);
                    $('.weekTotalTime').text(data.weekTotalTime);
                } else if (obj.status == 401) {
                    showToast(obj.message, 'error')
                    logout()
                } else {
                    showToast(obj.message, 'error')
                }
            }
        }
    );
}