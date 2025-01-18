$(document).ready(function () {

    $('#login-form').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                minlength: 6,
            },
        },
        messages: {
            email: {
                required: 'This field is required',
                email: 'Enter a valid email',
            },
            password: {
                minlength: 'Password must be at least 6 characters long'
            },
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            $('#' + element.attr('name') + '_error').html(error)
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});