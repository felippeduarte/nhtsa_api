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

        $url = $this->getUrl($modelYear, $manufacturer, $model);
        try {
            $result = $this->callAPI($url);
            return $this->formatResults($result);
        } catch (\Exception $e) {
            return $this->emptyResult();
        }
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
    private function formatResults($result)
    {
        $allowedKeys = ['Count', 'Results'];

        $result = array_filter($result, function($key) use($allowedKeys) {
            return in_array($key, $allowedKeys);
        }, ARRAY_FILTER_USE_KEY);

        $result['Results'] = array_map(function($r) {
            return [
                'Description' => $r['VehicleDescription'],
                'VehicleId' => $r['VehicleId'],
            ];
        }, $result['Results']);

        return $result;
    }

    /**
     * Mount the NTHSA API URL for further query
     *
     * @param int $modelYear
     * @param string $manufacturer
     * @param string $model
     * @return string
     */
    private function getUrl($modelYear, $manufacturer, $model)
    {
        return $this->baseUrl . "SafetyRatings/modelyear/$modelYear/make/$manufacturer/model/$model";
    }
}