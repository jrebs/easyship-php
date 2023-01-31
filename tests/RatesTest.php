<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class RatesTest extends TestCase
{
    public function test_hands_over_shipment()
    {
        $payload = ['test' => $this->faker->word];
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.easyship.com/2023-01/rates');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->rates()->request($payload);
    }
}
