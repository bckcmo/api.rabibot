<?php
namespace App\Bckcmo;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Bckcmo\Interfaces\GeoCoderInterface;

class GoogleGeoCoder implements GeoCoderInterface
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $zip;

    /**
     * GeoCoder constructor.
     *
     * @param array $config
     *
     */
    public function __construct($config)
    {
      $this->client = resolve('HttpClient');
      $this->endpoint = $config['endpoint'];
      $this->key = $config['key'];
    }

    /**
     * Sets the address data.
     *
     * @param array $data
     *
     * @return void
     */
    public function setAddress(array $data) : void
    {
      $this->address = $data['address'];
      $this->city = $data['city'];
      $this->zip = $data['zip'];
    }

    /**
     * Sends data to the EJScreen endpoint.
     *
     * @return array $result
     */
    public function geocode() : array
    {
      $url = "{$this->endpoint}?address={$this->address},+{$this->city},+{$this->zip}&key={$this->key}";
      $this->client->get($url);
      if(!$this->client->getStatusCode() == 200) {
        return ['success' => false];
      }

      $response = $this->client->getResponse();
      $result = [
        'lat' => $response['results']['0']['geometry']['location']['lat'],
        'lng' => $response['results']['0']['geometry']['location']['lng'],
      ];

      return ['success' => true, 'data' => $result];
    }
}
