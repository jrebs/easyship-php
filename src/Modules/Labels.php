<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Labels extends Module
{
    /**
     * Confirm and buy labels
     *
     * @link https://developers.easyship.com/v1.0/reference#confirm-and-buy-labels
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function buy(array $payload): ResponseInterface
    {
        $endpoint = '/label/v1/labels';

        return $this->easyship->request('post', $endpoint, $payload);
    }
}
