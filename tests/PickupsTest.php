<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class PickupsTest extends TestCase
{
    public function test_creates_pickup()
    {
        $payload = ['test' => $this->faker->word];
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.easyship.com/2023-01/pickups');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->pickups()->create($payload);
    }
}
