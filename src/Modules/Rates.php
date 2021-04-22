<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Rates extends Module
{
    /**
     * Request rates and taxes for a theoretical shipment.
     *
     * @link https://developers.easyship.com/v1.0/reference#request-rates-and-taxes
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(array $payload): ResponseInterface
    {
        $endpoint = '/rate/v1/rates';

        return $this->easyship->request('post', $endpoint, $payload);
    }
}
