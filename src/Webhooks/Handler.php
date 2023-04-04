<?php

namespace Easyship\Webhooks;

use Ahc\Jwt\JWT;
use Ahc\Jwt\JWTException;
use Easyship\Exceptions\InvalidPayloadException;
use Easyship\Exceptions\InvalidSignatureException;

class Handler
{
    /**
     * @var string[]
     */
    protected array $secretKeys;

    /**
     * @var \Ahc\Jwt\JWT
     */
    protected $jwt;

    /**
     * @var string[]
     */
    protected static $webhookEventTypes = [
        'shipment.label.created',
        'shipment.label.failed',
        'shipment.cancelled',
        'shipment.tracking.checkpoints.created',
        'shipment.tracking.status.changed',
        'shipment.warehouse.state.updated',
    ];

    /**
     * @var \Easyship\Webhooks\ListenerInterface[]
     */
    protected static $listeners = [];

    public function __construct(string ...$secretKeys)
    {
        $this->secretKeys = $secretKeys;
    }

    /**
     * This is the method that handles incoming webhook calls from Easyship.
     * Your application should pass the X-EASYSHIP-SIGNATURE header value as
     * the first argument and the contents of the post (already decoded from
     * JSON).
     *
     * If the signature fails to validate, the an instance of the
     * InvalidSignatureException will throw.
     *
     * If the signature is OK but no expected event_method is found, an
     * InvalidPayloadException will throw.
     *
     * If neither of those events occur, a 200 response will be immediately
     * sent to the client and the payload will be dispatched to the handler
     * method, where your overridden logic will run.
     *
     * You can short-certain almost all aspects of this flow by overriding
     * the various methods that are called.
     *
     * @param string $signature The value of the X-EAYSHIP-SIGNATURE header
     * @param array $payload The decoded json body of the post
     * @return void
     * @throws \Easyship\Exceptions\InvalidSignatureException
     * @throws \Easyship\Exceptions\InvalidPayloadException
     */
    public function handle(string $signature, array $payload): void
    {
        // Make sure the signature JWT validates
        $this->validateSignature($signature);

        // Parse event_type from payload and validate it
        $eventType = $this->extractEventTypeFromPayload($payload);

        // Easyship suggests instantly returning an HTTP 200 OK response
        // after recieving a webhook to ensure that they don't mark the post
        // as failed. Attach a listener to the 'webhook.validated' event if
        // you want to do this.
        $this->fireEvent('webhook.validated', $payload);

        // Finally, we fire the appropriate event
        $this->fireEvent($eventType, $payload);
    }

    /**
     * If there are any listeners associated with the event being fired, call
     * each of their fire methods, passing in the webhook payload.
     *
     * @param string $eventType
     * @param array $payload
     * @return void
     */
    public function fireEvent(string $eventType, array $payload): void
    {
        if (!array_key_exists($eventType, self::$listeners)) {
            return;
        }
        foreach (self::$listeners[$eventType] as $listener) {
            $listener->fire($payload);
        }
    }

    /**
     * Manually feed a JWT validator into this object so that getJwtValidator
     * will return this object instead of newing one up. The only reason we
     * don't just make the validator in the validateSignature method is
     * so that we can pass a mocked object in for the purpose of testing.
     *
     * @param JWT $jwt
     * @return void
     */
    public function setJwtValidator(JWT $jwt): void
    {
        $this->jwt = $jwt;
    }

    /**
     * Verify an easyship signature using the secretKey to decode it.
     *
     * @param string $signature
     * @return void
     * @throws \easyship\Exceptions\InvalidSignatureException
     */
    public function validateSignature(string $signature): void
    {
        foreach ($this->secretKeys as $secretKey) {
            if ($this->testSignature($secretKey, $signature)) {
                return;
            }
        }

        throw new InvalidSignatureException('No keys validated signature');
    }

    /**
     * Test a given signature against a secret key using the JWT library.
     *
     * @param string $secretKey
     * @param string $signature
     * @return boolean
     */
    public function testSignature(string $secretKey, string $signature): bool
    {
        try {
            $jwt = $this->jwt ?? new JWT($secretKey);
            $jwt->decode($signature,true);
        } catch (JWTException $e) {
            return false;
        }

        return true;
    }

    /**
     * Find the event_type in a webhook payload and return it. If oone is
     * not found or if the event_type isn't recognized as an expected one,
     * throw an exception.
     *
     * @param array $payload
     * @return string
     * @throws \Easyship\Exceptions\InvalidPayloadException
     */
    public function extractEventTypeFromPayload(array $payload): string
    {
        if (
            !array_key_exists('event_type', $payload)
            || !in_array($payload['event_type'], self::$webhookEventTypes)
        ) {
            throw new InvalidPayloadException(
                'no valid event type found in webhook payload'
            );
        }

        return $payload['event_type'];
    }

    /**
     * Register an event listener with the handler.
     *
     * @param string $eventType
     * @param ListenerInterface $listener
     * @return void
     */
    public static function addListener(
        string $eventType,
        ListenerInterface $listener
    ): void {
        static::$listeners[$eventType][] = $listener;
    }
}
