<?php
namespace App\Bckcmo;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Bckcmo\Interfaces\GeocoderInterface;

class GoogleGeocoder implements GeocoderInterface
{
    /**
     * @var Client
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
     * Geocoder constructor.
     *
     * @param array $config
     *
     */
    public function __construct($config)
    {
      // TODO: Client could be registered as a service provider and resolved via container.
      // Could create an interface and adapter around Guzzle that implements the interface, then add that to the container.
      $this->client = new Client();
      $this->endpoint = $config['endpoint'];
      $this->key = $config['key'];
    }

    /**
     * sets the address data
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
     * sends data to the EJScreen endpoint
     *
     * @return array $result
     */
    public function geocode() : array
    {
      $url = "{$this->endpoint}?address={$this->address},+{$this->city},+{$this->zip}&key={$this->key}";
      $response = $this->client->request('GET', $url);
      if(!$response->getStatusCode() == 200) {
        return ['success' => false];
      }

      $response = json_decode($response->getBody(), true);
      $result = [
        'lat' => $response['results']['0']['geometry']['location']['lat'],
        'lng' => $response['results']['0']['geometry']['location']['lng'],
      ];

      return ['success' => true, 'data' => $result];
    }
}
