<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use GuzzleHttp\Handler\MockHandler;

class VehiclesTest extends TestCase
{
    private $statusCodeOK = 200;

    public function testGetVehiclesFromGetOK()
    {
        $endPointResult = ['{"Count":4,"Message":"Results returned successfully","Results":[{"VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405},{"VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}'];
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = '{"Count":4,"Results":[{"Description":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"Description":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"Description":"2015 Audi A3 C AWD","VehicleId":9405},{"Description":"2015 Audi A3 C FWD","VehicleId":9406}]}';

        $this->get('/vehicles/2015/Audi/A3')
            ->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromGetShouldReturnCountZero()
    {
        $endPointResult = [$this->getEmptyEndpointResult()];
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = $this->getEmptyResult();

        $this->get('/vehicles/2013/Ford/Crown Victoria')
            ->seeJsonEquals(json_decode($expectedResult, true));

        $this->get('/vehicles/undefined/Audi/A3')
            ->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromGetWithRatingsOK()
    {
        $endPointResult[] = '{"Count":4,"Message":"Results returned successfully","Results":[{"VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405},{"VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}';
        $endPointResult[] = '{"Count":1,"Message":"Results returned successfully","Results":[{"VehiclePicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09005P005.jpg","OverallRating":"5","OverallFrontCrashRating":"4","FrontCrashDriversideRating":"4","FrontCrashPassengersideRating":"5","FrontCrashPicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09005P087.jpg","FrontCrashVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09005C016.wmv","OverallSideCrashRating":"5","SideCrashDriversideRating":"5","SideCrashPassengersideRating":"5","SideCrashPassengersideNotes":"These ratings do not apply to vehicles with optional torso/pelvis side air bags in the second row.","SideCrashPicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09008P108.jpg","SideCrashVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09008C012.wmv","RolloverRating":"4","RolloverRating2":"Not Rated","RolloverPossibility":0.1090,"RolloverPossibility2":0,"SidePoleCrashRating":"5","SidePolePicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09007P079.jpg","SidePoleVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09007C012.wmv","NHTSAForwardCollisionWarning":"Optional","NHTSALaneDepartureWarning":"Optional","ComplaintsCount":33,"RecallsCount":4,"InvestigationCount":0,"ModelYear":2015,"Make":"AUDI","Model":"A3","VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403}]}';
        $endPointResult[] = '{"Count":1,"Message":"Results returned successfully","Results":[{"VehiclePicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09005P005.jpg","OverallRating":"5","OverallFrontCrashRating":"4","FrontCrashDriversideRating":"4","FrontCrashPassengersideRating":"5","FrontCrashPicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09005P087.jpg","FrontCrashVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09005C016.wmv","OverallSideCrashRating":"5","SideCrashDriversideRating":"5","SideCrashPassengersideRating":"5","SideCrashPassengersideNotes":"These ratings do not apply to vehicles with optional torso/pelvis side air bags in the second row.","SideCrashPicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09008P108.jpg","SideCrashVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09008C012.wmv","RolloverRating":"4","RolloverRating2":"Not Rated","RolloverPossibility":0.1090,"RolloverPossibility2":0,"SidePoleCrashRating":"5","SidePolePicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09007P079.jpg","SidePoleVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09007C012.wmv","NHTSAForwardCollisionWarning":"Optional","NHTSALaneDepartureWarning":"Optional","ComplaintsCount":33,"RecallsCount":4,"InvestigationCount":0,"ModelYear":2015,"Make":"AUDI","Model":"A3","VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408}]}';
        $endPointResult[] = '{"Count":1,"Message":"Results returned successfully","Results":[{"OverallRating":"Not Rated","OverallFrontCrashRating":"Not Rated","FrontCrashDriversideRating":"Not Rated","FrontCrashPassengersideRating":"Not Rated","OverallSideCrashRating":"Not Rated","SideCrashDriversideRating":"Not Rated","SideCrashPassengersideRating":"Not Rated","RolloverRating":"Not Rated","RolloverRating2":"Not Rated","RolloverPossibility":0,"RolloverPossibility2":0,"SidePoleCrashRating":"Not Rated","NHTSAForwardCollisionWarning":"Optional","NHTSALaneDepartureWarning":"Optional","ComplaintsCount":33,"RecallsCount":4,"InvestigationCount":0,"ModelYear":2015,"Make":"AUDI","Model":"A3","VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405}]}';
        $endPointResult[] = '{"Count":1,"Message":"Results returned successfully","Results":[{"OverallRating":"Not Rated","OverallFrontCrashRating":"Not Rated","FrontCrashDriversideRating":"Not Rated","FrontCrashPassengersideRating":"Not Rated","OverallSideCrashRating":"Not Rated","SideCrashDriversideRating":"Not Rated","SideCrashPassengersideRating":"Not Rated","RolloverRating":"Not Rated","RolloverRating2":"Not Rated","RolloverPossibility":0,"RolloverPossibility2":0,"SidePoleCrashRating":"Not Rated","NHTSAForwardCollisionWarning":"Optional","NHTSALaneDepartureWarning":"Optional","ComplaintsCount":33,"RecallsCount":4,"InvestigationCount":0,"ModelYear":2015,"Make":"AUDI","Model":"A3","VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}';

        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = '{"Count":4,"Results":[{"CrashRating":"5","Description":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"CrashRating":"5","Description":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"CrashRating":"Not Rated","Description":"2015 Audi A3 C AWD","VehicleId":9405},{"CrashRating":"Not Rated","Description":"2015 Audi A3 C FWD","VehicleId":9406}]}';

        $this->get('/vehicles/2015/Audi/A3?withRating=true')
            ->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromGetWithRatingsWithErrorSubsequentCallShouldReturnCountZero()
    {
        $endPointResult[] = '{"Count":4,"Message":"Results returned successfully","Results":[{"VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405},{"VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}';
        //doesn't have OverallRating key
        $endPointResult[] = '{"Count":1,"Message":"Results returned successfully","Results":[{"VehiclePicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09005P005.jpg","OverallFrontCrashRating":"4","FrontCrashDriversideRating":"4","FrontCrashPassengersideRating":"5","FrontCrashPicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09005P087.jpg","FrontCrashVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09005C016.wmv","OverallSideCrashRating":"5","SideCrashDriversideRating":"5","SideCrashPassengersideRating":"5","SideCrashPassengersideNotes":"These ratings do not apply to vehicles with optional torso/pelvis side air bags in the second row.","SideCrashPicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09008P108.jpg","SideCrashVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09008C012.wmv","RolloverRating":"4","RolloverRating2":"Not Rated","RolloverPossibility":0.1090,"RolloverPossibility2":0,"SidePoleCrashRating":"5","SidePolePicture":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/images/2015/v09007P079.jpg","SidePoleVideo":"http://www.safercar.gov/staticfiles/DOT/safercar/ncapmedia/movies/2015/v09007C012.wmv","NHTSAForwardCollisionWarning":"Optional","NHTSALaneDepartureWarning":"Optional","ComplaintsCount":33,"RecallsCount":4,"InvestigationCount":0,"ModelYear":2015,"Make":"AUDI","Model":"A3","VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403}]}';

        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = $this->getEmptyResult();
        $this->get('/vehicles/2015/Audi/A3?withRating=true')
            ->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromGetWithRatingsNotTrueShouldReturnWithoutRatings()
    {
        $endPointResult = ['{"Count":4,"Message":"Results returned successfully","Results":[{"VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405},{"VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}'];
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = '{"Count":4,"Results":[{"Description":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"Description":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"Description":"2015 Audi A3 C AWD","VehicleId":9405},{"Description":"2015 Audi A3 C FWD","VehicleId":9406}]}';

        $this->get('/vehicles/2015/Audi/A3?withRating=false')
        ->seeJsonEquals(json_decode($expectedResult, true));

        $this->get('/vehicles/2015/Audi/A3?withRating=bananas')
        ->seeJsonEquals(json_decode($expectedResult, true));

        $this->get('/vehicles/2015/Audi/A3?withRating=1')
            ->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromGetWithRatingsInvalidModelYearShouldReturnCountZero()
    {
        $endPointResult = [$this->getEmptyEndpointResult()];
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = $this->getEmptyResult();

        $this->get('/vehicles/undefined/Audi/A3?withRating=true')
        ->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromPostOK()
    {
        $endPointResult = ['{"Count":4,"Message":"Results returned successfully","Results":[{"VehicleDescription":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"VehicleDescription":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"VehicleDescription":"2015 Audi A3 C AWD","VehicleId":9405},{"VehicleDescription":"2015 Audi A3 C FWD","VehicleId":9406}]}'];
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = '{"Count":4,"Results":[{"Description":"2015 Audi A3 4 DR AWD","VehicleId":9403},{"Description":"2015 Audi A3 4 DR FWD","VehicleId":9408},{"Description":"2015 Audi A3 C AWD","VehicleId":9405},{"Description":"2015 Audi A3 C FWD","VehicleId":9406}]}';

        $this->post('/vehicles', [
            'modelYear' => 2015,
            'manufacturer' => 'Audi',
            'model' => 'A3',
        ])->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromPostShouldReturnCountZero()
    {
        $endPointResult = [$this->getEmptyEndpointResult()];
        $this->mockGuzzle($endPointResult, $this->statusCodeOK);

        $expectedResult = $this->getEmptyResult();

        $this->post('/vehicles', [
            'modelYear' => 2013,
            'manufacturer' => 'Ford',
            'model' => 'Crown Victoria',
        ])->seeJsonEquals(json_decode($expectedResult, true));
    }

    public function testGetVehiclesFromPostMalformedInputShouldReturnCountZero()
    {
        $endPointResult = [$this->getEmptyEndpointResult()];

        $expectedResult = $this->getEmptyResult();

        $this->post('/vehicles', [
            'modelYear' => 'undefined',
            'manufacturer' => 'Audi',
            'model' => 'A3',
        ])->seeJsonEquals(json_decode($expectedResult, true));

        $this->post('/vehicles', [
            'manufacturer' => 'Audi',
            'model' => 'A3',
        ])->seeJsonEquals(json_decode($expectedResult, true));

        $this->post('/vehicles', [
            'modelYear' => '2015',
            'model' => 'A3',
        ])->seeJsonEquals(json_decode($expectedResult, true));

        $this->post('/vehicles', [
            'modelYear' => '2015',
            'manufacturer' => 'Audi',
        ])->seeJsonEquals(json_decode($expectedResult, true));
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
        $response = [];
        foreach ($endPointResult as $result) {
            $response[] = new \GuzzleHttp\Psr7\Response($statusCode, ['Content-Type' => 'application/json'], $result);
        }
        $guzzle = \Mockery::mock(GuzzleHttp\Client::class);
        $guzzle->shouldReceive('request')->andReturn(...$response);
        app()->instance('GuzzleHttp\Client', $guzzle);
    }

    /**
     * The NHTSA API return this data when there is no results
     *
     * @return void
     */
    private function getEmptyEndpointResult()
    {
        '{"Count":0,"Message":"No results found for this request","Results":[]}';
    }

    /**
     * This is the JSON the API should return when there is no results to display
     *
     * @return void
     */
    private function getEmptyResult()
    {
        return '{"Count":0,"Results":[]}';
    }
}
