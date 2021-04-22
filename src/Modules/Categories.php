<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Categories extends Module
{
    /**
     * Get a list of item categories
     *
     * @link https://developers.easyship.com/v1.0/reference#item-categories
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(): ResponseInterface
    {
        $endpoint = '/reference/v1/categories';

        return $this->easyship->request('get', $endpoint);
    }
}
