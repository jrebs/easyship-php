<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Stores extends Module
{
    /**
     * Returns a list of stores that are available with your account.
     *
     * @link https://developers.easyship.com/reference/stores_index
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(array $query = []): ResponseInterface
    {
        $endpoint = sprintf('%s/stores', self::API_VERSION);

        $this->easyship->request('get', $endpoint, $query);
    }
}
