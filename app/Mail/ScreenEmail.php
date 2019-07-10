<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScreenEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * @var App\Screen
   */
  public $screen;

  /**
   * @var string
   */
  public $resultText;

  /**
   * @var string
   */
  public $location;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($screen)
  {
    $this->screen = $screen;
    $this->resultText = $this->screen->ej_result ? 'is' : 'is not';
    $this->location = $this->screen->getLocation();
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->markdown('emails.screen');
  }

}
