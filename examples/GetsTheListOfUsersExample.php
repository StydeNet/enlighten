<?php

namespace Examples;

use Styde\Enlighten\Example;

class GetsTheListOfUsersExample extends Example
{
    public function getTitle(): string
    {
        return 'Obtiene la lista de usuarios';
    }

    public function getDescription(): ?string
    {
        return 'Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema';
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
        return 'api/users';
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
        return 'api/users/{status?}/{page?}';
    }

    public function getRouteParameters(): array
    {
        return array (
  0 => 
  array (
    'name' => 'status',
    'pattern' => '*',
    'optional' => true,
  ),
  1 => 
  array (
    'name' => 'page',
    'pattern' => '*',
    'optional' => true,
  ),
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
  'content-type' => 
  array (
    0 => 'application/json',
  ),
  'x-ratelimit-limit' => 
  array (
    0 => 60,
  ),
  'x-ratelimit-remaining' => 
  array (
    0 => 59,
  ),
);
    }

    public function getResponseStatus(): int
    {
        return 200;
    }

    public function getResponseBody()
    {
        return array (
  'data' => 
  array (
    0 => 
    array (
      'name' => 'Duilio Palacios',
      'email' => 'duilio@example.com',
    ),
    1 => 
    array (
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
