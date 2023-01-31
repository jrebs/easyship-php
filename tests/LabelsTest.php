<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class LabelsTest extends TestCase
{
    public function test_buys_labels()
    {
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.easyship.com/2023-01/labels');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->labels()->create([
            ['easyship_shipment_id' => $this->faker->word]
        ]);
    }
}
