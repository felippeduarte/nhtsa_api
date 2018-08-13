<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Services\NHTSAService;


/**
 * @OA\Info(
 *     title="NHTSA API - for Vehicles",
 *     version="0.1",
 *     @OA\Contact(
 *         email="felippduarte@gmail.com"
 *     )
 * )
 */

class VehiclesController extends Controller
{
    private $nhtsaService;

    public function __construct(NHTSAService $nhtsaService)
    {
        $this->nhtsaService = $nhtsaService;
    }

    /**
     * @OA\Get(
     *     path="/vehicles/{modelYear}/{manufacturer}/{model}",
     *     tags={"vehicles"},
     *     summary="Get information about Vehicle by Model Year, Manufacturer and Model",
     *     operationId="vehicles",
     *     @OA\Parameter(
     *         name="modelYear",
     *         in="path",
     *         description="Model Year",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="manufacturer",
     *         in="path",
     *         description="Manufacturer",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="model",
     *         in="path",
     *         description="Vehicle Model",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="withRating",
     *         in="query",
     *         description="Set to true to make the API return the vehicle Crash Rating",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Vehicle data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="Count",
     *                 type="integer"
     *             ),
     *             @OA\Property(
     *                 property="Result",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/VehicleResponse")
     *             )
     *         )
     *     )
     * )
     */
    public function getVehiclesFromGet(Request $request, $modelYear, $manufacturer, $model)
    {
        $withRating = ($request->input('withRating') === "true");

        $vehicleRequest = app()->makeWith('App\VehicleRequest', [
            'modelYear' => $modelYear,
            'manufacturer' => $manufacturer,
            'model' => $model,
        ]);

        return $this->getVehicles($vehicleRequest, $withRating);
    }

    /**
     * @OA\Post(
     *     path="/vehicles/",
     *     tags={"vehicles"},
     *     summary="Get information about Vehicle by Model Year, Manufacturer and Model",
     *     operationId="vehicles",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Vehicle information",
     *         @OA\JsonContent(ref="#/components/schemas/VehicleRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Vehicle data",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="Count",
     *                 type="integer"
     *             ),
     *             @OA\Property(
     *                 property="Result",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/VehicleResponse")
     *             )
     *         )
     *     )
     * )
     */
    public function getVehiclesFromPost(Request $request)
    {
        $modelYear = $request->input('modelYear');
        $manufacturer = $request->input('manufacturer');
        $model = $request->input('model');

        $vehicleRequest = app()->makeWith('App\VehicleRequest', [
            'modelYear' => $modelYear,
            'manufacturer' => $manufacturer,
            'model' => $model,
        ]);

        return $this->getVehicles($vehicleRequest, false);
    }

    private function getVehicles($vehicleRequest, $withRating)
    {
        if ($withRating) {
            $result = $this->nhtsaService->getVehiclesWithRatings($vehicleRequest);
        } else {
            $result = $this->nhtsaService->getVehicles($vehicleRequest);
        }
        return response()->json($result);
    }
}
