<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Pickups extends Module
{
    /**
     * Create a pickup
     *
     * @link https://developers.easyship.com/reference/pickups_create
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/pickups', self::API_VERSION);

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Cancel a pickup
     *
     * @link https://developers.easyship.com/reference/pickups_cancel
     * @param string $pickupId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cancel(string $pickupId): ResponseInterface
    {
        $endpoint = sprintf(
            '%s/pickups/%s/cancel',
            self::API_VERSION,
            $pickupId
        );

        return $this->easyship->request('post', $endpoint, []);
    }
}
