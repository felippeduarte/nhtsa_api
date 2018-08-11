<?php

namespace App\Services;

class NHTSAService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('NHTSA_API_ADDRESS');
    }

    /**
     * Call the NHTSA API and return formatted data
     *
     * @param int $modelYear
     * @param string $manufacturer
     * @param string $model
     * @return string
     */
    public function getVehicles($modelYear, $manufacturer, $model)
    {
        if(empty($modelYear) || empty($manufacturer) || empty($model)) {
            return $this->emptyResult();
        }

        $url = $this->getVehiclesUrl($modelYear, $manufacturer, $model);
        try {
            $response = $this->callAPI($url);
            return $this->formatVehiclesResults($response);
        } catch (\Exception $e) {
            return $this->emptyResult();
        }
    }

    public function getVehiclesWithRatings($modelYear, $manufacturer, $model)
    {
        $vehicles = $this->getVehicles($modelYear, $manufacturer, $model);

        //get the ratings for each founded vehicle
        if($vehicles['Count'] > 0) {
            foreach($vehicles['Results'] as &$result) {
                $url = $this->getVehiclesRatingsUrl($result['VehicleId']);
                try {
                    $ratings = $this->callAPI($url);
                    if($ratings['Count'] > 0) {
                        $result['CrashRating'] = $ratings['Results'][0]['OverallRating'];
                    }
                } catch (\Exception $e) {
                    //just ignore if an error occur
                }
            }
        }

        return $vehicles;
    }

    /**
     * Do the call to the API and return a
     *
     * @param string $url
     * @return array
     */
    private function callAPI($url)
    {
        $client = app('GuzzleHttp\Client');
        $result = $client->request('GET', $url);
        return json_decode($result->getBody(), true);
    }

    /**
     * Whenever there is an error, the API should return an empty result
     *
     * @return array
     */
    private function emptyResult()
    {
        return [
            'Count' => 0,
            'Results' => [],
        ];
    }

    /**
     * Eliminate/rename keys
     *
     * @param array $result
     * @return void
     */
    private function formatVehiclesResults($data)
    {
        $allowedKeys = ['Count', 'Results'];

        $data = array_filter($data, function($key) use($allowedKeys) {
            return in_array($key, $allowedKeys);
        }, ARRAY_FILTER_USE_KEY);

        $data['Results'] = array_map(function($r) {
            return [
                'Description' => $r['VehicleDescription'],
                'VehicleId' => $r['VehicleId'],
            ];
        }, $data['Results']);

        return $data;
    }

    /**
     * Mount the NTHSA API Vehicles URL for further query
     *
     * @param int $modelYear
     * @param string $manufacturer
     * @param string $model
     * @return string
     */
    private function getVehiclesUrl($modelYear, $manufacturer, $model)
    {
        return $this->baseUrl . "SafetyRatings/modelyear/$modelYear/make/$manufacturer/model/$model?format=json";
    }

    /**
     * Mount the NTHSA API RAtings URL for further query
     *
     * @param int $vehicleId
     * @return string
     */
    private function getVehiclesRatingsUrl($vehicleId)
    {
        return $this->baseUrl . "SafetyRatings/VehicleId/$vehicleId?format=json";
    }
}