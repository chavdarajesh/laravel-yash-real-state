function showToast(message, type) {
    // Set the message
    var typeClass;
    if (type) {
        if (type == 'success') {
            typeClass = 'bg-success-subtle text-success';
        } else if (type == 'error') {
            typeClass = 'bg-danger-subtle text-danger';
        } else if (type == 'warning') {
            typeClass = 'bg-warning-subtle text-warning';
        }
        $('#toast-body').text(message);
        $('#toast-div')
            .removeClass('bg-success-subtle text-success bg-danger-subtle text-danger bg-warning-subtle text-warning')
            .addClass(typeClass);
        var toastElement = new bootstrap.Toast($('#toast-div')[0]);
        toastElement.show();
    }
}
