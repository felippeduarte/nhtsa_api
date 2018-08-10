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

    public function getVehiclesFromGet($modelYear, $manufacturer, $model)
    {
        return $this->getVehicles($modelYear, $manufacturer, $model);
    }

    public function getVehiclesFromPost(Request $request)
    {
        $modelYear = $request->input('modelYear');
        $manufacturer = $request->input('manufacturer');
        $model = $request->input('model');

        return $this->getVehicles($modelYear, $manufacturer, $model);
    }

    private function getVehicles($modelYear, $manufacturer, $model)
    {
        $result = $this->nhtsaService->getVehicles($modelYear, $manufacturer, $model);
        return response()->json($result);
    }
}
