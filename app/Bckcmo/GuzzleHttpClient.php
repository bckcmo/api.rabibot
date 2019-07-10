<?php

namespace App\Bckcmo;

use GuzzleHttp\Client;
use App\Bckcmo\Interfaces\HttpClientInterface;

/**
 * Adapter for the Guzzle Client that implements the HttpClientInterface.
 */
class GuzzleHttpClient implements HttpClientInterface
{
  /**
   * @var Client
   */
  private $client;

  /**
   * @var HttpResponse
   */
  private $response;

  /**
   * GuzzleHttpClient constructor.
   */
  public function __construct(HttpResponse $response) {
    $this->client = new Client();
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
    $res = $this->client->request('GET', $endpoint);
    $this->setResponse($res);
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
    $res = $this->client->request('POST', $endpoint, ['body' => $data]);
    $this->setResponse($res);
    return $this->response;
  }

  /**
   * Sets response properties.
   *
   * @param array $res
   *
   */
  private function setResponse($res) : void {
    $this->setResponseStatusCode($res->getStatusCode());
    $this->setResponseData(json_decode($res->getBody(), true));
    $this->setResponseSuccess($res->getStatusCode() == 200);
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
   * @param array $data
   *
   */
  private function setResponseSuccess($success) : void {
    $this->response->setSuccess($success);
  }
}
