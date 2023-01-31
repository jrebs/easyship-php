<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Addresses extends Module
{
    /**
     * Retrieve a list of all addresses ordered by date of creation.
     *
     * @link https://developers.easyship.com/reference/addresses_index
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(array $query = []): ResponseInterface
    {
        $endpoint = sprintf('%s/addresses', self::API_VERSION);

        return $this->easyship->request('get', $endpoint, $query);
    }

    /**
     * Create an address.
     *
     * @link https://developers.easyship.com/reference/addresses_create
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/addresses', self::API_VERSION);

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * pdate an address in your account.
     *
     * @link https://developers.easyship.com/reference/addresses_update
     * @param string $addressId
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(string $addressId, array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/addresses/%s', self::API_VERSION, $addressId);

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Delete an address from your account.
     *
     * @link https://developers.easyship.com/reference/addresses_delete
     * @param string $addressId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(string $addressId): ResponseInterface
    {
        $endpoint = sprintf('%s/addresses/%s', self::API_VERSION, $addressId);

        return $this->easyship->request('post', $endpoint);
    }
}
