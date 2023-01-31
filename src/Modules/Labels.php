<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Labels extends Module
{
    /**
     * With the Label resource, you can confirm a Shipment that was created
     * using the Shipment API. Calling Buy Labels will confirm a Shipment
     * with the selected Courier and begin generating the Label & Shipping
     * Documents after checking your account’s balance is sufficient.
     *
     * @link https://developers.easyship.com/reference/labels_create
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/labels', self::API_VERSION);

        return $this->easyship->request('post', $endpoint, $payload);
    }
}
