<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Pickups extends Module
{
    /**
     * Retrieve available pickup slots
     *
     * @link https://developers.easyship.com/v1.0/reference#retrieve-available-pick-up-slots
     *
     * @param string $courierId
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(string $courierId): ResponseInterface
    {
        $endpoint = '/pickup/v1/pickup_slots/'.$courierId;

        return $this->easyship->request('get', $endpoint);
    }

    /**
     * Request a pickup
     *
     * @link https://developers.easyship.com/v1.0/reference#request-a-pickup
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(array $payload): ResponseInterface
    {
        $endpoint = '/pickup/v1/pickups';

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Mark as directly handed over
     *
     * @link https://developers.easyship.com/v1.0/reference#mark-as-handed-over
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handOver(array $payload): ResponseInterface
    {
        $endpoint = '/pickup/v1/direct_hand_over';

        return $this->easyship->request('post', $endpoint, $payload);
    }
}
