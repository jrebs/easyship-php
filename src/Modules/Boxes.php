<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Boxes extends Module
{
    /**
     * Retrieve a list of available courier boxes and your own boxes.
     *
     * @link https://developers.easyship.com/reference/boxes_index
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(): ResponseInterface
    {
        $endpoint = sprintf('%s/boxes', self::API_VERSION);

        return $this->easyship->request('get', $endpoint);
    }
}
