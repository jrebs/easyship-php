<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class TrackingTest extends TestCase
{
    public function test_gets_status()
    {
        $shipmentId = $this->faker->randomNumber();
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.easyship.com/track/v1/status?easyship_shipment_id=' . $shipmentId);
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->tracking()->status($shipmentId);
    }

    public function test_gets_checkpoints()
    {
        $shipmentId = $this->faker->randomNumber();
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.easyship.com/track/v1/checkpoints?easyship_shipment_id=' . $shipmentId);
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->tracking()->checkpoints($shipmentId);
    }
}
