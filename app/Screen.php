<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
  protected $fillable = [
      'address', 'city', 'state', 'zip', 'one_mile_report', 'blockgroup_report', 'ej_result'
  ];
}
