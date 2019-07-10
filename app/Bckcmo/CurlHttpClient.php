<?php

namespace App\Bckcmo;

use App\Bckcmo\Interfaces\HttpClientInterface;

class CurlHttpClient implements HttpClientInterface
{
  /**
   * @var HttpResponse
   */
  private $response;

  /**
   * CurlHttpClient constructor.
   */
  public function __construct(HttpResponse $response) {
    $this->response = $response;
  }

  /**
   * Method for http get requests.
   *
   * @param string $endpoint
   *
   * @return HttpResponse
   */
  public function get(string $endpoint) : HttpResponse {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_HEADER => 0,
      CURLOPT_URL => $endpoint,
      CURLOPT_FAILONERROR => true,
    ]);

    $res = curl_exec($curl);
    $this->setResponse(json_decode($res, true), curl_getinfo($curl, CURLINFO_HTTP_CODE));
    curl_close($curl);
    return $this->response;
  }

  /**
   * Method for http post requests.
   *
   * @param string $endpoint
   * @param array $data
   *
   * @return HttpResponse
   */
  public function post(string $endpoint, array $data) : HttpResponse {
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $endpoint,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_FAILONERROR => true,
    ]);

    $res = curl_exec($curl);
    $this->setResponse(json_decode($res, true), curl_getinfo($curl, CURLINFO_HTTP_CODE));
    return $this->response;
  }

  /**
   * Sets response properties.
   *
   * @param array $res
   * @param int $data
   *
   */
  private function setResponse($res, $status) : void {
    $this->setResponseStatusCode($status);
    $this->setResponseData($res);
    $this->setResponseSuccess($status == 200);
  }

  /**
   * Sets the response status code.
   *
   * @param int $status
   *
   */
  private function setResponseStatusCode($status) : void {
    $this->response->setStatusCode($status);
  }

  /**
   * Sets the response data property.
   *
   * @param array $data
   *
   */
  private function setResponseData($data) : void {
    $this->response->setData($data);
  }

  /**
   * Sets the response success property.
   *
   * @param boolean $success
   *
   */
  private function setResponseSuccess($success) : void {
    $this->response->setSuccess($success);
  }
}
