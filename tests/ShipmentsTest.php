<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class ShipmentsTest extends TestCase
{
    public function test_gets_shipment()
    {
        $shipmentId = $this->faker->word;
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with(
                'get',
                'https://api.easyship.com/2023-01/shipments/'.$shipmentId
            );
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->shipments()->get($shipmentId);
    }

    public function test_lists_shipments()
    {
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.easyship.com/2023-01/shipments');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->shipments()->list();
    }

    public function test_creates_shipments()
    {
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.easyship.com/2023-01/shipments');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->shipments()->create([]);
    }

    public function test_updates_shipment()
    {
        $shipmentId = $this->faker->word;
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with(
                'patch',
                'https://api.easyship.com/2023-01/shipments/'.$shipmentId
            );
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->shipments()->update($shipmentId, []);
    }

    public function test_deletes_shipment()
    {
        $shipmentId = $this->faker->word;
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with(
                'delete',
                'https://api.easyship.com/2023-01/shipments/'.$shipmentId
            );
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->shipments()->delete($shipmentId, []);
    }

    public function test_updates_warehouse_states()
    {
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with(
                'post',
                'https://api.easyship.com/2023-01/shipments/warehouse_state_updates'
            );
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->shipments()->warehouseStateUpdate([]);
    }
}
