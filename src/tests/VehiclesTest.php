<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use GuzzleHttp\Handler\MockHandler;

class VehiclesTest extends TestCase
{
    private $statusCodeOK = 200;

    public function testGetVehiclesFromGetOK()
    {
        $endPointResult = '{"Count":4,"Message":"Results returned successfully","Results":[{"VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405},{"VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}';
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $this->get('/vehicles/2015/Audi/A3');

        $expectedResult = '{"Count":4,"Results":[{"Description":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"Description":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"Description":"2015 Audi A3 C AWD","VehicleId":9405},{"Description":"2015 Audi A3 C FWD","VehicleId":9406}]}';
        $this->assertEquals($expectedResult, $this->response->getContent());
    }

    public function testGetVehiclesFromGetShouldReturnCountZero()
    {
        $endPointResult = '{"Count":0,"Message":"No results found for this request","Results":[]}';
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $this->get('/vehicles/2013/Ford/Crown Victoria');

        $expectedResult = '{"Count":0,"Results":[]}';
        $this->assertEquals($expectedResult, $this->response->getContent());
    }

    public function testGetVehiclesFromPostOK()
    {
        $endPointResult = '{"Count":4,"Message":"Results returned successfully","Results":[{"VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405},{"VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}';
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $this->post('/vehicles', [
            'modelYear' => 2015,
            'manufacturer' => 'Audi',
            'model' => 'A3',
        ]);

        $expectedResult = '{"Count":4,"Results":[{"Description":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"Description":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"Description":"2015 Audi A3 C AWD","VehicleId":9405},{"Description":"2015 Audi A3 C FWD","VehicleId":9406}]}';
        $this->assertEquals($expectedResult, $this->response->getContent());
    }

    public function testGetVehiclesFromPostShouldReturnCountZero()
    {
        $endPointResult = '{"Count":0,"Message":"No results found for this request","Results":[]}';
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $this->post('/vehicles', [
            'modelYear' => 2013,
            'manufacturer' => 'Ford',
            'model' => 'Crown Victoria',
        ]);

        $expectedResult = '{"Count":0,"Results":[]}';
        $this->assertEquals($expectedResult, $this->response->getContent());
    }

    public function testGetVehiclesFromPostMalformedInputShouldReturnCountZero()
    {
        $endPointResult = '{"Count":0,"Message":"No results found for this request","Results":[]}';
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $this->post('/vehicles', [
            'manufacturer' => 'Honda',
            'model' => 'Accord',
        ]);

        $expectedResult = '{"Count":0,"Results":[]}';
        $this->assertEquals($expectedResult, $this->response->getContent());
    }

    /**
     * Mock the Guzzle Client to avoid calls on NHTSA API
     *
     * @param string $endPointResult
     * @param int $statusCode
     * @return void
     */
    private function mockGuzzle($endPointResult, int $statusCode)
    {
        $response = new \GuzzleHttp\Psr7\Response($statusCode, ['Content-Type' => 'application/json'], $endPointResult);
        $guzzle = \Mockery::mock(GuzzleHttp\Client::class);
        $guzzle->shouldReceive('request')->once()->andReturn($response);
        app()->instance('GuzzleHttp\Client', $guzzle);
    }
}
