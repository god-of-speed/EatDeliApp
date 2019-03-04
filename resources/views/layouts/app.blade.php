<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EatDeli</title>
    <!-- Styles -->
    <!-- <link href="{{ mix('css/app.css') }}" rel="stylesheet"> -->
</head>
<body>
    <noscript>
        <strong>We're sorry but this app doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
        <app></app>
    </div>
    <script src="{{ mix('js/bootstrap.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
