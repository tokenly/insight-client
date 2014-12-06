<?php

use Tokenly\Insight\Client;
use \Exception;
use \PHPUnit_Framework_Assert as PHPUnit;

/*
*
* To run the second test, you must set these environment variables first
* export INSIGHT_CONNECTION_STRING="http://127.0.0.1:3000"


*/
class RequestTest extends \PHPUnit_Framework_TestCase
{


    public function testBuildRequest() {
        $insight_client = new Client($this->CONNECTION_STRING);
        $request = $insight_client->buildRequest('/tx/6ce4509eff0a955855e5a4690152e003982894be130491cccdb2af93cb9a68ab');
        // print $request."\n";

        PHPUnit::assertEquals('/api/tx/6ce4509eff0a955855e5a4690152e003982894be130491cccdb2af93cb9a68ab', $request->getPath());
        PHPUnit::assertEquals('GET', $request->getMethod());
        PHPUnit::assertEquals('application/json', $request->getHeader('Content-Type'));
    } 


    public function testSendRequests() {
        if (!$this->CONNECTION_IS_SET) {
            $this->markTestIncomplete("Please define environment var INSIGHT_CONNECTION_STRING to run this test.");
            return;
        }

        $insight_client = new Client($this->CONNECTION_STRING);
        $response_data = $insight_client->getTransaction('6ce4509eff0a955855e5a4690152e003982894be130491cccdb2af93cb9a68ab');
        // echo json_encode($response_data, 192)."\n";

        PHPUnit::assertNotEmpty($response_data);
        PHPUnit::assertEquals('6ce4509eff0a955855e5a4690152e003982894be130491cccdb2af93cb9a68ab', $response_data['txid']);
        PHPUnit::assertEquals('1', $response_data['version']);

        $response_data = $insight_client->getBlock('000000000000000015f697b296584d9d443d2225c67df9033157a9efe4a8faa0');
        // echo json_encode($response_data, 192)."\n";

        PHPUnit::assertNotEmpty($response_data);
        PHPUnit::assertEquals('000000000000000015f697b296584d9d443d2225c67df9033157a9efe4a8faa0', $response_data['hash']);
        PHPUnit::assertEquals('2', $response_data['version']);
    } 

    public function testSendRequestError() {
        if (!$this->CONNECTION_IS_SET) {
            $this->markTestIncomplete("Please define environment var INSIGHT_CONNECTION_STRING to run this test.");
            return;
        }

        // expects exception
        $exception_caught = false;
        $insight_client = new Client($this->CONNECTION_STRING);
        try {
            $response_data = $insight_client->getTransaction('badbadtxid');
        } catch (Exception $e) {
            $exception_caught = true;            
        }

        PHPUnit::assertTrue($exception_caught);
        PHPUnit::assertEquals(400, $e->getCode());
    } 



    public function setup() {
        $this->CONNECTION_STRING = getenv('INSIGHT_CONNECTION_STRING') ?: 'http://localhost:3000';
        $this->CONNECTION_IS_SET = strlen($this->CONNECTION_STRING) ? true : false;
    }

}
