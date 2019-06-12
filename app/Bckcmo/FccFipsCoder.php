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
      $this->client = resolve('HttpClient');
      $this->uriData = $config;
    }

    /**
     * Sets the geoData data.
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
     * Sends data to the EJScreen endpoint.
     *
     * @return array $result
     */
    public function fipscode() : array
    {
      $url = "{$this->uriData['uri']}{$this->lat}{$this->uriData['lat_query']}{$this->lng}{$this->uriData['lng_query']}";
      $response = $this->client->get($url);
      if(!$response->success()) {
        return ['success' => false, 'status' => $response->getStatusCode()];
      }

      $result = [
        'fipscode' => substr($response->getData()['Block']['FIPS'], 0, -3),
      ];

      return ['success' => true, 'data' => $result];
    }
}
