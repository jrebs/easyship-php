<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class ItemCategories extends Module
{
    /**
     * Get a list of item categories
     *
     * @link https://developers.easyship.com/reference/item_categories_index
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(): ResponseInterface
    {
        $endpoint = sprintf('%s/item_categories', self::API_VERSION);

        return $this->easyship->request('get', $endpoint);
    }
}
