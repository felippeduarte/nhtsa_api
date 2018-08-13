<?php

namespace App\Services;

class NHTSAService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('NHTSA_BASE_API_ADDRESS');
    }

    /**
     * Call the NHTSA API and return formatted data
     *
     * @param int $modelYear
     * @param string $manufacturer
     * @param string $model
     * @return array
     */
    public function getVehicles($vehicleRequest)
    {
        if (!$this->validateInput($vehicleRequest)) {
            return $this->emptyResult();
        }

        $url = $this->getVehiclesUrl($vehicleRequest);

        try {
            $response = $this->callAPI($url);
            return $this->formatVehiclesResults($response);
        } catch (\Exception $e) {
            return $this->emptyResult();
        }
    }

    /**
     * Call the NHTSA API and return formatted data
     * For each vehicle found, do a subsequent call and get the OverallRating as CrashRating
     *
     * @param int $modelYear
     * @param string $manufacturer
     * @param string $model
     * @return array
     */
    public function getVehiclesWithRatings($vehicleRequest)
    {
        if (!$this->validateInput($vehicleRequest)) {
            return $this->emptyResult();
        }

        $vehicles = $this->getVehicles($vehicleRequest);

        //get the ratings for each founded vehicle
        if ($vehicles['Count'] > 0) {
            //to avoid multiple maps to the response array
            //we use the array directly instead of VehicleResponse object
            foreach ($vehicles['Results'] as &$vehicleResponse) {
                $url = $this->getVehiclesRatingsUrl($vehicleResponse['VehicleId']);

                try {
                    $ratings = $this->callAPI($url);

                    if ($ratings['Count'] > 0) {
                        $vehicleResponse['CrashRating'] = $ratings['Results'][0]['OverallRating'];
                    }
                } catch (\Exception $e) {
                    //if there is an error in any call, return an empty result
                    return $this->emptyResult();
                }
            }
        }

        return $vehicles;
    }

    /**
     * Validate the input for Vehicles request
     *
     * @param string $modelYear
     * @param string $manufacturer
     * @param string $model
     * @return boolean
     */
    private function validateInput($vehicleRequest)
    {
        if (empty($vehicleRequest->modelYear) ||
            !is_numeric($vehicleRequest->modelYear) ||
            empty($vehicleRequest->manufacturer) ||
            empty($vehicleRequest->model)
        ) {
            return false;
        }

        return true;
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
     * @return array
     */
    private function formatVehiclesResults($data)
    {
        $allowedKeys = ['Count', 'Results'];

        //filter the result set only with Allowed Keys
        $data = array_filter($data, function ($key) use ($allowedKeys) {
            return in_array($key, $allowedKeys);
        }, ARRAY_FILTER_USE_KEY);

        $data['Results'] = array_map(function ($r) {
            $vehicleResponse = app()->makeWith('App\VehicleResponse', [
                'description' => $r['VehicleDescription'],
                'vehicleId' => $r['VehicleId'],
            ]);
            return $vehicleResponse->toArray();
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
    private function getVehiclesUrl($vehicleRequest)
    {
        return $this->baseUrl . "SafetyRatings/modelyear/$vehicleRequest->modelYear/make/$vehicleRequest->manufacturer/model/$vehicleRequest->model?format=json";
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
