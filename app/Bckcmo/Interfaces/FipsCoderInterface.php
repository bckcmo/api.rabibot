<?php

namespace App\Bckcmo\Interfaces;

interface FipsCoderInterface
{
  public function setGeoData(array $data) : void;
  public function fipscode() : array;
}
