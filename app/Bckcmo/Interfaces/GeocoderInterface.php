<?php

namespace App\Bckcmo\Interfaces;

interface GeoCoderInterface
{
  public function setAddress(array $data) : void;
  public function geocode() : array;
}
