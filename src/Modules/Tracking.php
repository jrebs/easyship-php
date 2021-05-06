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
     * @param string $shipmentId
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function status(string $shipmentId): ResponseInterface
    {
        $endpoint = '/track/v1/status?easyship_shipment_id=' . $shipmentId;

        return $this->easyship->request('get', $endpoint);
    }

    /**
     * Get shipment tracking checkpoints
     *
     * @link https://developers.easyship.com/v1.0/reference#checkpoints
     *
     * @param string $shipmentId
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function checkpoints(string $shipmentId): ResponseInterface
    {
        $endpoint = '/track/v1/checkpoints?easyship_shipment_id=' . $shipmentId;

        return $this->easyship->request('get', $endpoint);
    }
}
