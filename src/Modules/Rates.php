<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Rates extends Module
{
    /**
     * Request rates and taxes for a theoretical shipment.
     *
     * @link https://developers.easyship.com/reference/rates_request
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/rates', self::API_VERSION);

        return $this->easyship->request('post', $endpoint, $payload);
    }
}
