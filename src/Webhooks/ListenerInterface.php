<?php

namespace Easyship\Webhooks;

interface ListenerInterface
{
    /**
     * All webhook event listeners will accept an array that represents the
     * webhook payload sent from Easyship.
     *
     * @param array $payload
     *
     * @return void
     */
    public function fire(array $payload): void;
}
