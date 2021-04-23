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
            ->with('post', 'https://api.easyship.com/label/v1/labels');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->labels()->buy([
            ['easyship_shipment_id' => $this->faker->word]
        ]);
    }
}
