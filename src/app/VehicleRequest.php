<?php

namespace App;

/**
 * @OA\Schema(
 *     title="VehicleRequest",
 *     type="object"
 * )
 */
class VehicleRequest
{
    /**
     *  @OA\Property(
     *      property="modelYear",
     *      description="Model Year",
     *      type="integer"
     *  )
     */
    public $modelYear;

    /**
     *  @OA\Property(
     *      property="manufacturer",
     *      description="Manufacturer",
     *      type="string"
     *  )
     */
    public $manufacturer;

    /**
     *  @OA\Property(
     *      property="model",
     *      description="Model",
     *      type="string"
     *  )
     */
    public $model;

    public function __construct($modelYear, $manufacturer, $model)
    {
        $this->modelYear = $modelYear;
        $this->manufacturer = $manufacturer;
        $this->model = $model;
    }
}
