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
   * Sets the status code property
   *
   * @param int $code
   */
  public function setStatusCode(int $code) {
      $this->statusCode = $code;
  }

  /**
   * Sets the data property
   *
   * @param array $data
   */
  public function setData(array $data) {
      $this->data = $data;
  }

  /**
   * Sets the success property
   *
   * @param int $success
   */
  public function setSuccess(int $success) {
      $this->success = $success;
  }

  /**
   * Gets the status code property
   *
   * @return int
   */
  public function getStatusCode() {
    return $this->statusCode;
  }

  /**
   * Gets the data property
   *
   * @return array
   */
  public function getData() {
      return $this->data;
  }

  /**
   * Gets the success property
   *
   * @return boolean
   */
  public function success() {
      return $this->success;
  }
}
