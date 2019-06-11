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
      $this->address = str_replace(' ', '+', $data['address']);
      $this->city = str_replace(' ', '+', $data['city']);
      $this->zip = str_replace(' ', '+', $data['zip']);
    }

    /**
     * Sends data to the EJScreen endpoint.
     *
     * @return array $result
     */
    public function geocode() : array
    {
      $url = "{$this->endpoint}?address={$this->address},+{$this->city},+{$this->zip}&key={$this->key}";
      $response = $this->client->get($url);
      if(!$response->success()) {
        return ['success' => false];
      }

      $data = $response->getData();
      $result = [
        'lat' => $data['results']['0']['geometry']['location']['lat'],
        'lng' => $data['results']['0']['geometry']['location']['lng'],
      ];

      return ['success' => true, 'data' => $result];
    }
}
