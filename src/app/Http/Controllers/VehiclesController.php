<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Services\NHTSAService;

class VehiclesController extends Controller
{
    private $nhtsaService;

    public function __construct(NHTSAService $nhtsaService)
    {
        $this->nhtsaService = $nhtsaService;
    }

    public function getVehiclesFromGet(Request $request, $modelYear, $manufacturer, $model)
    {
        $withRating = ($request->input('withRating') === "true");
        return $this->getVehicles($modelYear, $manufacturer, $model, $withRating);
    }

    public function getVehiclesFromPost(Request $request)
    {
        $modelYear = $request->input('modelYear');
        $manufacturer = $request->input('manufacturer');
        $model = $request->input('model');

        return $this->getVehicles($modelYear, $manufacturer, $model);
    }

    private function getVehicles($modelYear, $manufacturer, $model, $withRating = false)
    {
        if ($withRating) {
            $result = $this->nhtsaService->getVehiclesWithRatings($modelYear, $manufacturer, $model);
        } else {
            $result = $this->nhtsaService->getVehicles($modelYear, $manufacturer, $model);
        }
        return response()->json($result);
    }
}
