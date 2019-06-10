<?php
namespace App\Bckcmo;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class EJScreenApi
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
     * @var GeoCoderInterface
     */
    private $geocoder;

    /**
     * EJScreenApi constructor.
     *
     * @param $config
     *
     */
    public function __construct(array $config, $geocoder)
    {
      // TODO: Client could be registered as a service provider and resolved via container.
      // Could create an interface and adapter around Guzzle that implements the interface, then add that to the container.
      $this->client = new Client();
      $this->uriData = $config;
      $this->geocoder = $geocoder;
    }

    /**
     * sends data to the EJScreen endpoint
     *
     * @param $data
     *
     */
    public function get($data)
    {
      $this->geocoder->setAddress($data);
      $geoData = $this->geocoder->geocode();
      if(!$geoData['success']) {
        return ['success' => false];
      }
      $geoData = $geoData['data'];
      $url = "{$this->uriData['uri']}{$geoData['lng']}{$this->uriData['lng_query']}{$geoData['lat']}{$this->uriData['lat_query']}";
      $response = $this->client->request('GET', $url);
      return [
        'success' => true,
        'data' => [
          'is_ej' => !empty($this->processResults(json_decode($response->getBody(), true))),
          'lat' => $geoData['lat'],
          'lng' => $geoData['lng'],
        ],
      ];
    }

    /**
     * fitlers API response and return array of state and national indecies that are greater than or equal to 80
     *
     * @param array $data
     *
     * @return array
     */
    private function processResults($data)
    {
      return array_filter($data, function($value, $key) {
        if(is_string($key)) {
          if((substr($key, 0, 3) == 'S_P' || substr($key, 0, 3) == 'N_P')) {
            return $value >= 80;
          }
        }
      }, ARRAY_FILTER_USE_BOTH);
    }
}
