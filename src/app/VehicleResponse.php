<?php

namespace App;

/**
 * @OA\Schema(
 *     title="VehicleResponse",
 *     type="object"
 * )
 */
class VehicleResponse
{
    /**
     *  @OA\Property(
     *      property="VehicleId",
     *      description="Vehicle ID",
     *      type="integer"
     *  )
     */
    public $vehicleId;

    /**
     *  @OA\Property(
     *      property="CrashRating",
     *      description="Crash Rating",
     *      type="string"
     *  )
     */
    public $crashRating;

    /**
     *  @OA\Property(
     *      property="Description",
     *      description="Description",
     *      type="string"
     *  )
     */
    public $description;

    public function __construct($description, $vehicleId)
    {
        $this->description = $description;
        $this->vehicleId = $vehicleId;
    }

    /**
     * Transform the object into array
     *    modifying the keys to match the expected response
     *
     * @return void
     */
    public function toArray()
    {
        $array = [
            'Description' => $this->description,
            'VehicleId' => $this->vehicleId,
        ];

        if (!empty($this->crashRating)) {
            $array['CrashRating'] = $this->crashRating;
        }

        return $array;
    }
}
