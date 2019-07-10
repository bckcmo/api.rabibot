<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable, HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * Get the screens for the user.
   */
  public function screens()
  {
    return $this->hasMany('App\Screen');
  }

  /**
   * Check if user owns the screen.
   *
   * @param Screen $screen
   */
  public function owns($screen) {
    return $this->can('crud', $screen);
  }

  /**
   * Returns user email and id.
   *
   * @return array
   */
  public function getUserData() {
    return [
      'user_id' => $this->id,
      'email' => $this->email
    ];
  }

}
