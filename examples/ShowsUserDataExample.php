<?php

namespace Examples;

use Styde\Enlighten\Example;

class ShowsUserDataExample extends Example
{
    public function getTitle(): string
    {
        return 'Get user data by ID';
    }

    public function getDescription(): ?string
    {
        return 'Retrieves the public-user data';
    }

    public function getRequestHeaders(): array
    {
        return array (
  'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
  'accept-language' => 'en-us,en;q=0.5',
  'accept-charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
);
    }

    public function getRequestMethod(): string
    {
        return 'GET';
    }

    public function getRequestPath(): string
    {
        return 'user/1';
    }

    public function getRequestQueryParameters(): array
    {
        return array (
);
    }

    public function getRequestInput(): array
    {
        return array (
);
    }

    public function getRoute(): string
    {
        return 'user/{user}';
    }

    public function getRouteParameters(): array
    {
        return array (
  0 => 
  array (
    'name' => 'user',
    'pattern' => '\\d+',
    'optional' => false,
  ),
);
    }

    public function getResponseHeaders(): array
    {
        return array (
  'content-type' => 
  array (
    0 => 'text/html; charset=UTF-8',
  ),
  'cache-control' => 
  array (
    0 => 'no-cache, private',
  ),
  'date' => 
  array (
    0 => 'Tue, 22 Sep 2020 10:08:00 GMT',
  ),
  'set-cookie' => 
  array (
    0 => 'XSRF-TOKEN=eyJpdiI6ImkrdTdzZVVyUENGdkt5cnd2LzdBNVE9PSIsInZhbHVlIjoiNnFieUdWeEJqSm5DMWhLdmxIZU5pWkNoWk0rR3BBN3hFc3Z2THBjdzY5RWVsekFRdnZkVElsdkhtT3E3RTdlQlNXVnE3MkNjZ0w0dHMvdTdRQlhGNmdqWEwzb2FYVUNhMWYrVm9GQWRGSzlETmZuYks2TjVWYnlxbEk3Q25nZ2UiLCJtYWMiOiIzNzdlNTBkY2YxM2ZiYmQ2OGUzMDFmYjZmNWY3NTkxNzY2MWU5YWEyMmFhZjBjMGVjNWEyNWU5NzlhMDViNWNkIn0%3D; expires=Tue, 22-Sep-2020 12:08:00 GMT; Max-Age=7200; path=/; samesite=lax',
    1 => 'laravel_session=eyJpdiI6Ik1pUWRFa0NNVjUrSFczZ24wM1dNZ3c9PSIsInZhbHVlIjoiT2hyL05jcDFWRTk0MkdHenZvaGZuTFVJUlpUUGE3QVNWR3FTTHZmc1ZoK0tQd1F2RmR3Z3NHay9LZk55bUF6Vm5RdDAwR0hMVnBhMExpeTdXamtQRWRiN0ZHTWZZRDhHYjJwVE5haWdpakhqNDdKVUFOR29CVGxlWW9mYnRaZGgiLCJtYWMiOiI0ZDI1MzFiNjVmMmNlMjc0NjA1OThiYjFlNDM3NjdhMWRiZGJiNmNhYzU3OTEyZDI5YjQwOTYwMWY0NWZjMjI4In0%3D; expires=Tue, 22-Sep-2020 12:08:00 GMT; Max-Age=7200; path=/; httponly; samesite=lax',
  ),
);
    }

    public function getResponseStatus(): int
    {
        return 200;
    }

    public function getResponseBody()
    {
        return '<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://localhost/css/prism.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.min.js" defer></script>
</head>
<body class="bg-gray-900">

<p>Duilio Palacios</p>

<p>user@example.test</p>

<script src="http://localhost/js/prism.js"></script>
</body>
</html>
';
    }

    public function getResponseTemplate(): ?string
    {
        return '
@extends(\'layout\')

@section(\'content\')
<p>{{ $user->name }}</p>

<p>{{ $user->email }}</p>
@endsection
';
    }
}
