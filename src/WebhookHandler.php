<?php

namespace Easyship;

use Ahc\Jwt\JWT;

class WebhookHandler
{
    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var \Ahc\Jwt\JWT
     */
    protected $jwt;

    /**
     * @param string $secretKey
     */
    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * This is the method that handles incoming webhook calls from Easyship.
     * Your application should pass the X-EASYSHIP-SIGNATURE header value as
     * the first argument and the contents of the post (already decoded from
     * JSON).
     *
     * If the signature fails to validate, the webhookSignatureInvalid method
     * will be fired and a 401 returned to the client.
     *
     * If the signature is OK but no expected event_method is found, the
     * webhookEventTypeInvalid method will fire and a 400 returned.
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
     *
     * @return void
     */
    public function handle(string $signature, array $payload): void
    {
        if (!$this->validateSignature($signature)) {
            // You can circumvent the following behaviour by overriding the
            // webhookSignatureInvalid() method and exiting before execution
            // would return to this point.
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        $method = $this->getEventMethod($payload['event_type'] ?? null);
        if (!$this->eventMethodExists($method)) {
            // You can circumvent the following behaviour by overriding the
            // webhookEventTypeInvalid() method and exiting before execution
            // would return to this point.
            header('HTTP/1.0 400 Bad Request');
            exit;
        }

        // Easyship suggests an immediate HTTP 200 response to avoid them
        // assuming a bad response (which will get your webhook disabled if
        // it occurs enough times in succession). If you don't want this to
        // happen, just override the sendOkHeader() method. You'll then need
        // to ensure that all of your webhook handler override methods send
        // an OK response at some point.
        $this->sendOkHeaderNow();

        // Finally, we fire the appropriate event method override.
        $this->$method($payload);
    }

    /**
     * Get a reference to the JWT object for validating tokens or make one
     * if we don't have one yet.
     *
     * @return JWT
     */
    public function getJwtValidator(): JWT
    {
        if (!$this->jwt) {
            $this->jwt = new JWT($this->secretKey);
        }

        return $this->jwt;
    }

    /**
     * Manually feed a JWT validator into this object so that getJwtValidator
     * will return this object instead of newing one up. The only reason we
     * don't just make the validator in the validateSignature method is
     * so that we can pass a mocked object in for the purpose of testing.
     *
     * @param JWT $jwt
     *
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
     *
     * @return boolean
     */
    public function validateSignature(string $signature): bool
    {
        try {
            $this->getJwtValidator()->decode($signature, true);
        } catch (\Exception $e) {
            $this->webhookSignatureInvalid($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Translate an event type string into its handler method name and then
     * verify that a method exists on this object with that name, returning
     * the appropriate method or else throwing an exception if unable to find
     * a matching method.
     *
     * @param string $eventType
     *
     * @return string
     */
    public function getEventMethod(string $eventType): string
    {
        $parts = explode('.', $eventType);
        $finalParts = [array_shift($parts)];
        foreach ($parts as $part) {
            $finalParts[] = ucwords($part);
        }
        $method = join('', $finalParts);

        return $method;
    }

    /**
     * Make sure the determined event method name exists.
     *
     * @param string $method
     *
     * @return bool
     */
    public function eventMethodExists(string $method): bool
    {
        if (!method_exists($this, $method)) {
            $this->webhookEventTypeInvalid($method);
            return false;
        }

        return true;
    }

    /**
     * Send a 200 OK header immediately to the client but allow our code
     * to continue executing.
     *
     * @return void
     */
    public function sendOkHeaderNow(): void
    {
        header('HTTP/1.0 200 OK');
        flush();
    }

    public function webhookSignatureInvalid(string $message): void
    {
        //
    }

    public function webhookEventTypeInvalid(string $eventType): void
    {
        //
    }

    public function shipmentLabelCreated(array $payload): void
    {
        //
    }

    public function shipmentLabelFailed(array $payload): void
    {
        //
    }

    public function shipmentCancelled(array $payload): void
    {
        //
    }

    public function shipmentTrackingCheckpointsCreated(array $payload): void
    {
        //
    }

    public function shipmentTrackingStatusChanged(array $payload): void
    {
        //
    }

    public function shipmentWarehouseStateUpdated(array $payload): void
    {
        //
    }
}
