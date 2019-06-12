<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
  protected $fillable = [
      'address', 'city', 'state', 'zip', 'one_mile_report', 'blockgroup_report', 'ej_result'
  ];

  /**
   * Sets the address properities.
   *
   * @param array $data
   */
  public function setAddress($data) {
    $this->address = $data['address'];
    $this->city = $data['city'];
    $this->state = $data['state'];
    $this->zip = $data['zip'];
  }

  /**
   * Get the user that owns the sceen.
   */
  public function user()
  {
      return $this->belongsTo('App\User');
  }
}
