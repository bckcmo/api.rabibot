<?php
namespace App\Bckcmo;

use Log;

class EJScreenApi
{
    /**
     * @var HttpClientInterface
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
      $this->client = resolve('HttpClient');
      $this->uriData = $config;
      $this->geocoder = $geocoder;
    }

    /**
     * Sends data to the EJScreen endpoint.
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
      $this->client->get($url);
      return [
        'success' => true,
        'data' => [
          'is_ej' => !empty($this->processResults($this->client->getResponse())),
          'lat' => $geoData['lat'],
          'lng' => $geoData['lng'],
        ],
      ];
    }

    /**
     * Fitlers API response and return array of state and national indicies that are greater than or equal to 80.
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
