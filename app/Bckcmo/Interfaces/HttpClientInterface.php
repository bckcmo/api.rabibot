<?php

namespace App\Bckcmo\Interfaces;

use App\Bckcmo\HttpResponse;

interface HttpClientInterface
{
  public function get(string $data) : HttpResponse;
  public function post(string $endpoint, array $data) : HttpResponse;
}
