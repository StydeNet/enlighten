<?php

namespace Tests\Suites\Unit;

use PHPUnit\Framework\TestCase;
use Styde\Enlighten\Example;

class ExampleTest extends TestCase
{
    /** @test */
    function gets_the_full_path_of_the_request()
    {
        $example = new class extends ApiExample {
            public function getRequestQueryParameters(): array
            {
                return [];
            }
        };;

        $this->assertSame('api/users', $example->full_path);

        $example = new class extends ApiExample {
            public function getRequestQueryParameters(): array
            {
                return ['page' => 2, 'status' => 'active'];
            }
        };

        $this->assertSame('api/users?page=2&status=active', $example->full_path);
    }

    /** @test */
    function gets_the_response_type_in_a_readable_format()
    {
        $example = new ApiExample;

        $this->assertSame('JSON', $example->response_type);

        $example = new HtmlExample;

        $this->assertSame('HTML', $example->response_type);
    }
}

class ApiExample extends Example
{
    public function getTitle(): string
    {
        return 'Gets the list of users';
    }

    public function getDescription(): ?string
    {
        return null;
    }

    public function getRequestHeaders(): array
    {
        return array(
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
        return 'api/users';
    }

    public function getRequestQueryParameters(): array
    {
        return array();
    }

    public function getRequestInput(): array
    {
        return [];
    }

    public function getRoute(): string
    {
        return '';
    }

    public function getRouteParameters(): array
    {
        return [];
    }

    public function getResponseHeaders(): array
    {
        return array(
            'cache-control' =>
                array(
                    0 => 'no-cache, private',
                ),
            'date' =>
                array(
                    0 => 'Sun, 20 Sep 2020 10:47:59 GMT',
                ),
            'content-type' =>
                array(
                    0 => 'application/json',
                ),
            'x-ratelimit-limit' =>
                array(
                    0 => 60,
                ),
            'x-ratelimit-remaining' =>
                array(
                    0 => 59,
                ),
        );
    }

    public function getResponseStatus(): int
    {
        return 200;
    }

    public function getResponseBody(): array
    {
        return array(
            'data' =>
                array(
                    0 =>
                        array(
                            'name' => 'Duilio Palacios',
                            'email' => 'duilio@example.com',
                        ),
                    1 =>
                        array(
                            'name' => 'Jeffer Ochoa',
                            'email' => 'jeff.ochoa@example.com',
                        ),
                ),
        );
    }

    public function getResponseTemplate(): ?string
    {
        return NULL;
    }
}

class HtmlExample extends Example
{
    public function getTitle(): string
    {
        return 'Shows user data';
    }

    public function getDescription(): ?string
    {
        return null;
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
        return 'user/3';
    }

    public function getRequestQueryParameters(): array
    {
        return array (
        );
    }

    public function getRequestInput(): array
    {
        return [];
    }

    public function getRoute(): string
    {
        return '';
    }

    public function getRouteParameters(): array
    {
        return [];
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
                    0 => 'Sun, 20 Sep 2020 10:47:59 GMT',
                ),
            'set-cookie' =>
                array (
                    0 => 'XSRF-TOKEN=eyJpdiI6IlMrK0xQL0t0NldORHZ5TFBZWEQ2Q1E9PSIsInZhbHVlIjoiQ0VoT3FLR3N6S2NxRUplWmNTR0RKL1NaWkhpdU1YUVJ3cFpldE02RStOK0VLM2hiMy93ZDB4Mk9pUmp5T1pac3F5b1NaZzBmRzErbHEvcGpKdlZ4a3F1WkN6elZWVUNYekRQcWhjVis0L1h2Skl5ZVdNdkROdVN1cXltY013OFciLCJtYWMiOiJkMmJmZDgyYjUzOWE1MDM2NDk2YTYxNDlhMzEzYzUzOGNmYjdkOTk1ZjFkYzIzYzM5MjM5ZDI0N2NiYWJmZjNmIn0%3D; expires=Sun, 20-Sep-2020 12:47:59 GMT; Max-Age=7200; path=/; samesite=lax',
                    1 => 'laravel_session=eyJpdiI6IjRCcG1zSHA5akRzMWk3RmhTVXJOa1E9PSIsInZhbHVlIjoiUTkxMTY0MGR5MlMyVjFxbnZ3N1ovczBnV2N0cHZWWFA4VDNTNEFJMmsyNERQVmFReFhBdEhOV3cycTdreUdSNFQvZ21FWHNvbjBUZmtHdTFiTUZTQUdpYnc2b2lKeUVRemVFcmNobThRWURvR2NPMU5lSDRsL0RLQ1Z5SVFmZHAiLCJtYWMiOiIwYmVjMTNlNmI2YjgxZTBiMTU1YTI0NzJkNTQyNzUyNWUxYmEyNWFlNzE1YmI2MDM3MTAwNDM5NTViMjM0NTViIn0%3D; expires=Sun, 20-Sep-2020 12:47:59 GMT; Max-Age=7200; path=/; httponly; samesite=lax',
                ),
        );
    }

    public function getResponseStatus(): int
    {
        return 200;
    }

    public function getResponseBody(): string
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
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.21.0/themes/prism-coy.min.css" integrity="sha512-CKzEMG9cS0+lcH4wtn/UnxnmxkaTFrviChikDEk1MAWICCSN59sDWIF0Q5oDgdG9lxVrvbENSV1FtjLiBnMx7Q==" crossorigin="anonymous" />
</head>
<body>

<div class="container mx-auto rounded-lg bg-gray-100 my-12">
    <p>Duilio Palacios</p>

<p>user@example.test</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/prismjs@1.21.0/prism.min.js"></script>
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
