<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Products extends Module
{
    /**
     * Returns a list of products that are available with your account.
     *
     * @link https://developers.easyship.com/reference/products_index
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(array $query = []): ResponseInterface
    {
        $endpoint = sprintf('%s/products', self::API_VERSION);

        return $this->easyship->request('get', $endpoint, $query);
    }

    /**
     * Create a single product into your account.
     *
     * @link https://developers.easyship.com/reference/products_create
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/products', self::API_VERSION);

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Delete a single product from your account.
     *
     * @link https://developers.easyship.com/reference/products_delete
     * @param string $productId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(string $productId): ResponseInterface
    {
        $endpoint = sprintf('%s/products/%s', self::API_VERSION, $productId);

        return $this->easyship->request('delete', $endpoint);
    }

    /**
     * Update a single product in your account.
     *
     * @link https://developers.easyship.com/reference/products_update
     * @param string $productId
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(string $productId, array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/products/%s', self::API_VERSION, $productId);

        return $this->easyship->request('patch', $endpoint, $payload);
    }
}
