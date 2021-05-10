<?php

namespace Easyship\Tests;

use Easyship\EasyshipRequest;

class EasyshipRequestTest extends TestCase
{
    public function test_gets_properties()
    {
        $method = $this->faker->word;
        $uri = $this->faker->word;
        $payload = [$this->faker->word => $this->faker->word];
        $request = new EasyshipRequest($method, $uri, $payload);
        $this->assertEquals($method, $request->getMethod());
        $this->assertEquals($uri, $request->getUri());
        $this->assertEquals($payload, $request->getPayload());
    }
}
