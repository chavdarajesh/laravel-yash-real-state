<script type="text/javascript" src="{{ asset('assets/front/js/jquery-3.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/front/js/bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/front/js/custom.js') }}"></script>

<script src="{{ asset('custom-assets/default/front/js/toastr.min.js') }}"></script>

<script>
    @if(Session::has('message'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "preventDuplicates": false
    }
    toastr.success("{{ session('message') }}");
    @endif

    @if(Session::has('error'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "preventDuplicates": false
    }
    toastr.error("{{ session('error') }}");
    @endif

    @if(Session::has('info'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "preventDuplicates": false
    }
    toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "preventDuplicates": false
    }
    toastr.warning("{{ session('warning') }}");
    @endif
</script>

@yield('js')


<script>
    // $(document).ready(function() {
    //     $('#newsletter-form').validate({
    //         rules: {
    //             email: {
    //                 required: true,
    //                 email: true
    //             }
    //         },
    //         messages: {

    //             email: {
    //                 required: 'This field is required',
    //                 email: 'Enter a valid email',
    //             }
    //         },
    //         errorPlacement: function(error, element) {
    //             error.addClass('text-white');
    //             $('#' + element.attr('name') + 'nl_error').html(error)
    //         },
    //         highlight: function(element, errorClass, validClass) {
    //             $(element).addClass('border border-danger');
    //         },
    //         unhighlight: function(element, errorClass, validClass) {
    //             $(element).removeClass('border border-danger');
    //         },
    //         submitHandler: function(form) {
    //             form.submit();
    //         }
    //     });
    // });
</script>
