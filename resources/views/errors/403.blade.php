<!DOCTYPE html>

<html lang="en" class="light-style" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ env('APP_NAME', 'Laravel App') }} | Error - Pages </title>

    <meta name="description" content="" />

    <!-- Favicon -->
    @include('admin.layouts.head')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/css/pages/page-misc.css') }}" />
</head>

<body>
    <!-- Content -->

    <!-- Error -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-2 mx-2">{{ $exception->getMessage() }} :(</h2>
            <p class="mb-4 mx-2">Oops! 😖 the web server has recognized a user's request but cannot grant access.</p>
            <a href="{{ route('front.home') }}" class="btn btn-primary">Back to home</a>
            <div class="mt-3">
                <img src="{{ asset('assets/admin/img/illustrations/girl-doing-yoga-light.png') }}"
                    alt="girl-doing-yoga-light" width="500" class="img-fluid"
                    data-app-dark-img="illustrations/page-misc-error-dark.png"
                    data-app-light-img="illustrations/girl-doing-yoga-light.png" />
            </div>
        </div>
    </div>
    <!-- /Error -->

    <!-- / Content -->

    @include('admin.layouts.footer')
</body>

</html>
