<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Track extends Module
{
    /**
     * Get shipment tracking status
     *
     * @link https://developers.easyship.com/v1.0/reference#status
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function status(array $payload): ResponseInterface
    {
        $endpoint = '/track/v1/status';

        return $this->easyship->request('get', $endpoint, $payload);
    }

    /**
     * Get shipment tracking checkpoints
     *
     * @link https://developers.easyship.com/v1.0/reference#checkpoints
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function checkpoints(array $payload): ResponseInterface
    {
        $endpoint = '/track/v1/checkpoints';

        return $this->easyship->request('get', $endpoint, $payload);
    }
}
