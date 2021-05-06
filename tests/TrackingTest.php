<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class TrackingTest extends TestCase
{
    public function test_gets_status()
    {
        $shipmentId = $this->faker->randomNumber();
        $params = [
            'easyship_shipment_id' => $shipmentId,
        ];
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $uri = 'https://api.easyship.com/track/v1/status?' .
            http_build_query($params);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', $uri);
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->tracking()->status($params);
    }

    public function test_gets_checkpoints()
    {
        $shipmentId = $this->faker->randomNumber();
        $params = [
            'easyship_shipment_id' => $shipmentId,
        ];
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.easyship.com/track/v1/checkpoints?' .
                http_build_query($params));
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->tracking()->checkpoints($params);
    }
}
