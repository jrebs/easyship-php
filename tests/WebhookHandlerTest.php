<?php

namespace Easyship\Tests;

use Ahc\Jwt\JWT;
use Easyship\Exceptions\InvalidPayloadException;
use Easyship\Exceptions\InvalidSignatureException;
use Easyship\Webhooks\Handler;
use Easyship\Webhooks\ListenerInterface;
use Throwable;
use TypeError;

class WebhookHandlerTest extends TestCase
{
    public function test_sets_jwt_validator()
    {
        $handler = new Handler($this->faker->word);
        // Incorrect object type throws TypeError
        $this->expectException(\TypeError::class);
        $handler->setJwtValidator(new \stdClass());
        // Correct object type doesn't fuss
        $exceptionThrown = false;
        try {
            $jwt = new JWT($this->faker->word);
        } catch (\Throwable $e) {
            $exceptionThrown = true;
        }
        $this->assertFalse($exceptionThrown);
    }

    public function test_gets_jwt_validator()
    {
        $handler = new Handler($this->faker->word);
        $jwt = new JWT($this->faker->word);
        $handler->setJwtValidator($jwt);
        $this->assertEquals($jwt, $handler->getJwtValidator());
    }

    public function test_validates_signatures_bad()
    {
        $key = $this->faker->word;
        $signature = $this->faker->word;
        $handler = new Handler($key);
        $this->expectException(InvalidSignatureException::class);
        $handler->validateSignature($signature);
    }

    public function test_validates_signatures_good()
    {
        $key = $this->faker->word;
        $signature = $this->faker->word;
        $jwt = $this->createMock(JWT::class);
        $jwt->expects($this->once())
            ->method('decode')
            ->with($signature)
            ->willReturn([]);
        $handler = new Handler($key);
        $handler->setJwtValidator($jwt);
        $thrown = false;
        try {
            $handler->validateSignature($signature);
        } catch (Throwable $e) {
            $thrown = true;
        }
        $this->assertFalse($thrown);
    }

    public function test_extracts_event_type_from_payload()
    {
        $payload = ['event_type' => 'shipment.cancelled'];
        $handler = new Handler($this->faker->word);
        $this->assertEquals(
            'shipment.cancelled',
            $handler->extractEventTypeFromPayload($payload)
        );
    }

    public function test_throws_on_unexpected_event_type_extraction()
    {
        $payload = ['event_type' => 'test.event'];
        $handler = new Handler($this->faker->word);
        $this->expectException(InvalidPayloadException::class);
        $handler->extractEventTypeFromPayload($payload);
    }

    public function test_adds_listeners()
    {
        $listener = new class implements ListenerInterface {
            public function fire(array $payload): void { }
        };
        Handler::addListener('test.event', $listener);
        $this->expectException(TypeError::class);
        Handler::addListener('bad.type', new \stdClass());
    }

    public function test_fires_events()
    {
        $handler = new Handler($this->faker->word);
        // bad event type
        $this->assertNull($handler->fireEvent('invalid.event', []));
        // good event type with no listeners
        $this->assertNull($handler->fireEvent('shipment.cancelled', []));
        // good event type with a mocked listener
        $payload = ['foo' => $this->faker->word];
        $mock = $this->createMock(TestListener::class);
        $mock->expects($this->once())
            ->method('fire')
            ->with($payload);
        Handler::addListener('test.event', $mock);
        $handler->fireEvent('test.event', $payload);
    }

    public function test_handles_events()
    {
        $payload = ['event_type' => 'shipment.cancelled'];
        $mock = $this->createMock(TestListener::class);
        $mock->expects($this->once())
            ->method('fire')
            ->with($payload);
        Handler::addListener('shipment.cancelled', $mock);
        $handler = new Handler($this->faker->word);
        $key = $this->faker->word;
        $signature = $this->faker->word;
        $jwt = $this->createMock(JWT::class);
        $jwt->expects($this->once())
            ->method('decode')
            ->with($signature)
            ->willReturn([]);
        $handler = new Handler($key);
        $handler->setJwtValidator($jwt);
        $handler->handle($signature, $payload);
    }
}
