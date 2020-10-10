<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex,nofollow">
    <title>Laravel Enlighten</title>
    <link rel="stylesheet" href="/vendor/enlighten/css/app.css">
</head>
<body class="bg-gray-900">

    <x-enlighten-app-layout>
        <x-slot name="title">{{ $title ?? 'Dashboard' }}</x-slot>
        {{ $slot }}
    </x-enlighten-app-layout>

    <script src="/vendor/enlighten/js/build.js"></script>\

</body>
</html>
