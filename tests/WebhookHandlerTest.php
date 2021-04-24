<?php

namespace Easyship\Tests;

use Ahc\Jwt\JWT;
use Easyship\WebhookHandler;

class WebhookHandlerTest extends TestCase
{
    public function test_constructor_requires_a_key()
    {
        $this->expectException(\ArgumentCountError::class);
        $handler = new WebhookHandler();
    }

    public function test_translates_event_type_to_method_name()
    {
        $handler = new WebhookHandler($this->faker->word);
        $this->assertEquals(
            'shipmentLabelFailed',
            $handler->getEventMethod('shipment.label.failed')
        );
        $this->assertEquals(
            'firstSecondThirdFourth',
            $handler->getEventMethod('first.second.third.fourth')
        );
    }

    public function test_event_method_existence_validates()
    {
        $handler = new WebhookHandler($this->faker->word);
        $this->assertFalse($handler->eventMethodExists($this->faker->word));
        $this->assertTrue($handler->eventMethodExists('shipmentCancelled'));
    }

    public function test_sets_jwt_validator()
    {
        $handler = new WebhookHandler($this->faker->word);
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
        $handler = new WebhookHandler($this->faker->word);
        $jwt = new JWT($this->faker->word);
        $handler->setJwtValidator($jwt);
        $this->assertEquals($jwt, $handler->getJwtValidator());
    }

    public function test_validates_signatures_bad()
    {
        $key = $this->faker->word;
        $signature = $this->faker->word;
        $handler = new WebhookHandler($key);
        $this->assertFalse(
            $handler->validateSignature($signature)
        );
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
        $handler = new WebhookHandler($key);
        $handler->setJwtValidator($jwt);
        $this->assertTrue(
            $handler->validateSignature($signature)
        );
    }

    public function test_required_methods_exist()
    {
        $handler = new WebhookHandler($this->faker->word);
        $this->assertTrue(method_exists($handler, 'webhookSignatureInvalid'));
        $this->assertTrue(method_exists($handler, 'webhookEventTypeInvalid'));
        $this->assertTrue(method_exists($handler, 'shipmentLabelCreated'));
        $this->assertTrue(method_exists($handler, 'shipmentLabelFailed'));
        $this->assertTrue(method_exists($handler, 'shipmentCancelled'));
        $this->assertTrue(method_exists($handler, 'shipmentTrackingCheckpointsCreated'));
        $this->assertTrue(method_exists($handler, 'shipmentTrackingStatusChanged'));
        $this->assertTrue(method_exists($handler, 'shipmentWarehouseStateUpdated'));
    }
}
