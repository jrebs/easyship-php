<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Tracking extends Module
{
    /**
     * Get shipment tracking status
     *
     * @link https://developers.easyship.com/v1.0/reference#status
     *
     * @param array $query Associative array of query string params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function status(array $query): ResponseInterface
    {
        $endpoint = '/track/v1/status';

        return $this->easyship->request('get', $endpoint, $query);
    }

    /**
     * Get shipment tracking checkpoints
     *
     * @link https://developers.easyship.com/v1.0/reference#checkpoints
     *
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function checkpoints(array $query): ResponseInterface
    {
        $endpoint = '/track/v1/checkpoints';

        return $this->easyship->request('get', $endpoint, $query);
    }
}
