<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;

/**
 * @internal
 */
final class HistoricalDataTest extends CIUnitTestCase
{
    use ControllerTestTrait;

    public function testHistoricalDataFetchWithEmptyRegion()
    {
        $params = ['symbol' => 'PFIN', 'region' => null];

        $response = (new FetchHistoricalData())->handle($params);

        $success = $response['success'];
        $code = $response['code'];
        $data = $response['data'];

        $this->assertTrue($success && $code == 200 && sizeof($data) > 0);
    }

    public function testHistoricalDataFetchWithIncorrectSymbol()
    {
        $params = ['symbol' => 'PFINN', 'region' => 'US'];

        $response = (new FetchHistoricalData())->handle($params);

        $success = $response['success'];
        $code = $response['code'];
        $data = $response['data'];

        $this->assertTrue(!$success && $code == 404 && sizeof($data) == 0);
    }
}
