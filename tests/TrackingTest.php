<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class TrackingTest extends TestCase
{
    public function test_gets_checkpoints()
    {
        $shipmentId = $this->faker->randomNumber();
        $params = [
            'easyship_shipment_id' => $shipmentId,
        ];
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.easyship.com/2023-01/shipments/trackings?' .
                http_build_query($params));
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->shipments()->trackings($params);
    }
}
