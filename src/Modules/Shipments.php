<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Shipments extends Module
{
    /**
     * Retrieve a shipment's details
     *
     * @link https://developers.easyship.com/v1.0/reference#get-a-shipment
     *
     * @param string $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(string $shipmentId): ResponseInterface
    {
        $endpoint = '/shipment/v1/shipments/'.$shipmentId;

        return $this->easyship->request('get', $endpoint);
    }

    /**
     * Retrieve a list of shipments
     *
     * @link https://developers.easyship.com/v1.0/reference#get-shipments
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(): ResponseInterface
    {
        $endpoint = '/shipment/v1/shipments';

        return $this->easyship->request('get', $endpoint);
    }

    /**
     * Create a shipment
     *
     * @link https://developers.easyship.com/v1.0/reference#create-a-shipment
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(array $payload): ResponseInterface
    {
        $endpoint = '/shipment/v1/shipments';

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Create a shipment and request the label in a single request
     *
     * @link https://developers.easyship.com/v1.0/reference#create-a-shipment-and-buy-label
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createAndBuyLabel(array $payload): ResponseInterface
    {
        $endpoint = '/shipment/v1/shipments/create_and_buy_label';

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Update a shipment
     *
     * @link https://developers.easyship.com/v1.0/reference#update-a-shipment
     *
     * @param string $id
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(string $shipmentId, array $payload): ResponseInterface
    {
        $endpoint = '/shipment/v1/shipments/'.$shipmentId;

        return $this->easyship->request('patch', $endpoint, $payload);
    }

    /**
     * Delete a shipment
     *
     * @link https://developers.easyship.com/v1.0/reference#delete-a-shipment
     *
     * @param string $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(string $shipmentId): ResponseInterface
    {
        $endpoint = '/shipment/v1/shipments/'.$shipmentId;

        return $this->easyship->request('delete', $endpoint);
    }

    /**
     * Update the warehouse state of one or more shipments
     *
     * @link https://developers.easyship.com/v1.0/reference#update-shipment-warehouse-status
     *
     * @param array $payload
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateWarehouseState(array $payload): ResponseInterface
    {
        $endpoint = '/shipment/v1/shipments/update_warehouse_state';

        return $this->easyship->request('patch', $endpoint, $payload);
    }
}
