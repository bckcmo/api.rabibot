<?php
namespace App\Bckcmo;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Bckcmo\Interfaces\FipsCoderInterface;

class FccFipsCoder implements FipsCoderInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $uriData;

    /**
     * @var string
     */
    private $lat;

    /**
     * @var string
     */
    private $lng;

    /**
     * FipsCoder constructor.
     *
     * @param array $config
     *
     */
    public function __construct($config)
    {
      // TODO: Client could be registered as a service provider and resolved via container.
      // Could create an interface and adapter around Guzzle that implements the interface, then add that to the container.
      $this->client = new Client();
      $this->uriData = $config;
    }

    /**
     * sets the geoData data
     *
     * @param array $data
     *
     * @return void
     */
    public function setGeoData(array $data) : void
    {
      $this->lat = $data['lat'];
      $this->lng = $data['lng'];
    }

    /**
     * sends data to the EJScreen endpoint
     *
     * @return array $result
     */
    public function fipscode() : array
    {
      $url = "{$this->uriData['uri']}{$this->lat}{$this->uriData['lat_query']}{$this->lng}{$this->uriData['lng_query']}";
      $response = $this->client->request('GET', $url);
      if(!$response->getStatusCode() == 200) {
        return ['success' => false];
      }

      $response = json_decode($response->getBody(), true);
      $result = [
        'fipscode' => substr($response['Block']['FIPS'], 0, -3),
      ];

      return ['success' => true, 'data' => $result];
    }
}
