<?php

namespace App\Bckcmo\Interfaces;

interface GeocoderInterface
{
  public function setAddress(array $data) : void;
  public function geocode() : array;
}
