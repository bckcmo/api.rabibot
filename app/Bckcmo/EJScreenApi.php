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
  public function screenAddress(array $data)
  {
    $this->geocoder->setAddress($data);
    $geoData = $this->geocoder->geocode();

    if(!$geoData['success']) {
      return ['success' => false];
    }
    $geoData = $geoData['data'];
    return $this->screenCoordinates($geoData);
  }

  /**
   * Sends data to the EJScreen endpoint.
   *
   * @param $data
   *
   */
  public function screenCoordinates($data)
  {
    $this->validateScreenCoords($data);

    $url = "{$this->uriData['uri']}{$data['lng']}{$this->uriData['lng_query']}{$data['lat']}{$this->uriData['lat_query']}";

    $response = $this->client->get($url);

    if(!$response->success()) {
      return ['success' => false, 'data' => []];
    }

    return [
      'success' => true,
      'data' => [
        'ej_result' => !empty($this->processResults($response->getData())),
        'lat' => $data['lat'],
        'lng' => $data['lng'],
      ],
    ];
  }

  /**
   * Ensures that the supplied data array contains the information required by EJSCREEN
   *
   * @param array $data
   *
   * @throws InvalidArgumentException
   */
  private function validateScreenCoords(array $data)
  {
    if(!array_key_exists('lat', $data) || !array_key_exists('lng', $data)) {
      throw new InvalidArgumentException('EJSCREEN Coordinates array must contain lat and lng keys.');
    }
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
