<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
  protected $fillable = [
    'address', 'city', 'state', 'zip', 'lat', 'lng', 'ej_result', 'user_id'
  ];

  /**
   * Get the user that owns the sceen.
   *
   * @return \App\User
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  /**
   * Check if the address fields are popualated.
   *
   * @return boolean
   */
  public function hasAddress() {
    return isset($this->address);
  }

  /**
   * Returns a formatted string that has the address location (if applicable), or GPS coordinates.
   *
   * @return string
   */
  public function getLocation() {
    return $this->hasAddress() ? $this->getFullAddress() : $this->getCoordinates();
  }

  /**
   * Returns a formatted string of the full address.
   *
   * @return string
   */
  public function getFullAddress() {
    return "{$this->address} {$this->city}, {$this->state} {$this->zip}";
  }

  /**
   * Returns a formatted string of the GPS coordinates.
   *
   * @return string
   */
  public function getCoordinates() {
    return "{$this->lat}, {$this->lng}";
  }
}
