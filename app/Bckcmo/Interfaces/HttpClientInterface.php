<?php

namespace App\Bckcmo\Interfaces;

interface HttpClientInterface
{
  public function get(string $data) : void;
  public function post(string $endpoint, array $data) : void;
  public function getStatusCode() : int;
  public function getResponse() : array;
}
