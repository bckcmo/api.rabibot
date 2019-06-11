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

  public function get(string $endpoint) : HttpResponse {
    $res = $this->client->request('GET', $endpoint);
    $this->setResponse($res);
    return $this->response;
  }

  public function post(string $endpoint, array $data) : HttpResponse {
    $res = $this->client->request('POST', $endpoint, ['body' => $data]);
    $this->setResponse($res);
    return $this->response;
  }

  private function setResponse($res) {
    $this->setResponseStatusCode($res->getStatusCode());
    $this->setResponseData(json_decode($res->getBody(), true));
    $this->setResponseSuccess($res->getStatusCode() == 200);
  }

  private function setResponseStatusCode($status) : void {
    $this->response->setStatusCode($status);
  }

  private function setResponseData($data) : void {
    $this->response->setData($data);
  }

  private function setResponseSuccess($success) : void {
    $this->response->setSuccess($success);
  }
}
