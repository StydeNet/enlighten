<?php

namespace Examples;

use Styde\Enlighten\Example;

class CreatesANewUserExample extends Example
{
    public function getTitle(): string
    {
        return 'Creates a new user';
    }

    public function getDescription(): ?string
    {
        return NULL;
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
        return 'POST';
    }

    public function getRequestPath(): string
    {
        return 'user';
    }

    public function getRequestQueryParameters(): array
    {
        return array (
);
    }

    public function getRequestInput(): array
    {
        return array (
  'name' => 'Duilio',
  'email' => 'duilio@example.test',
  'password' => 'my-password',
);
    }

    public function getRoute(): string
    {
        return 'user';
    }

    public function getRouteParameters(): array
    {
        return array (
);
    }

    public function getResponseHeaders(): array
    {
        return array (
  'cache-control' => 
  array (
    0 => 'no-cache, private',
  ),
  'date' => 
  array (
    0 => 'Tue, 22 Sep 2020 10:08:00 GMT',
  ),
  'location' => 
  array (
    0 => 'http://localhost',
  ),
  'content-type' => 
  array (
    0 => 'text/html; charset=UTF-8',
  ),
  'set-cookie' => 
  array (
    0 => 'XSRF-TOKEN=eyJpdiI6IjVxeXFCTW5aTnpEUDEybkNjRFpvNUE9PSIsInZhbHVlIjoiZG53VlNCb250M3BhaitvNUk0VXowcDh2R25NcnhPSUJ3VXpyakxqR21xSG1rY01LVjRxVTZleDFuMFk5K08rVjNXVzdJNnlyWHdPb0lESGlaS2ltZHdEN0lsbXFGSzVUaGVIQU1LMXN6cjFjbWxGb2pENnEwc0ZPU0VsNUg2K3YiLCJtYWMiOiI2YTA2ZGE3NjQyNTdmMWE2YmU1M2EwY2NjZmNiMDg5NWE2MDIzNTZjYTg2NTNhZmJmOWMwOWUxYjA2N2MyZTE0In0%3D; expires=Tue, 22-Sep-2020 12:08:00 GMT; Max-Age=7200; path=/; samesite=lax',
    1 => 'laravel_session=eyJpdiI6ImhSZmkzTlBhWjgyRTgrSlVEenp3OVE9PSIsInZhbHVlIjoiNzFUY3dyUWRBbUJvbzhycFdiNmxFb2pzbmsrS253aVhrZ2RpZnJJT25TY29URnRsWWc1UEZxUTczMmxXbkVJUDdJTXBtUkNFVC9aU05MeC9EdldpWXpJRmQ2RDRoTUYva29qWUcyTGdWM3lHOHBuSnBtVmdBckJybTVQSVpTQVIiLCJtYWMiOiI5ZDZhZGFhYTEyNzJiZTgzODc1ZGRhYWQ5ZGY3ZGMyNDljNzljY2U0YTg0YTZkNTJjNzZkNjAzMjIzOTJmODcwIn0%3D; expires=Tue, 22-Sep-2020 12:08:00 GMT; Max-Age=7200; path=/; httponly; samesite=lax',
  ),
);
    }

    public function getResponseStatus(): int
    {
        return 302;
    }

    public function getResponseBody()
    {
        return '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url=\'http://localhost\'" />

        <title>Redirecting to http://localhost</title>
    </head>
    <body>
        Redirecting to <a href="http://localhost">http://localhost</a>.
    </body>
</html>';
    }

    public function getResponseTemplate(): ?string
    {
        return NULL;
    }
}
