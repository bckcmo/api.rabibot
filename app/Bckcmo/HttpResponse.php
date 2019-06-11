<?php

namespace App\Bckcmo;

class HttpResponse
{
  /**
   * @param int
   */
  private $statusCode;

  /**
   * @param array
   */
  private $data;

  /**
   * @param int
   */
  private $success;

  /**
   *
   */
  public function setStatusCode(int $code) {
      $this->statusCode = $code;
  }

  /**
   *
   */
  public function setData(array $data) {
      $this->data = $data;
  }

  /**
   *
   */
  public function setSuccess(int $success) {
      $this->success = $success;
  }

  /**
   *
   */
  public function getStatusCode() {
    return $this->code;
  }

  /**
   *
   */
  public function getData() {
      return $this->data;
  }

  /**
   *
   */
  public function success() {
      return $this->success;
  }
}
