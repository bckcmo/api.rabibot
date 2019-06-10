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
   * @var GuzzleHttp\Psr7\Response
   */
  private $response;

  /**
   * GuzzleHttpClient constructor.
   */
  public function __construct() {
    $this->client = new Client();
    $this->response = null;
  }

  public function get(string $endpoint) : void {
    $this->response = $this->client->request('GET', $endpoint);
  }

  public function post(string $endpoint, array $data) : void {
    $this->response = $this->client->request('POST', $endpoint, ['body' => $data]);
  }

  public function getStatusCode() : int {
    return $this->response->getStatusCode();
  }

  public function getResponse() : array {
    return json_decode($this->response->getBody(), true);
  }
}
