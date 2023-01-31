<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Shipments extends Module
{
    /**
     * Retrieve a list of shipments
     *
     * @link https://developers.easyship.com/reference/shipments_index
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(array $query = []): ResponseInterface
    {
        $endpoint = sprintf('%s/shipments', self::API_VERSION);

        return $this->easyship->request('get', $endpoint, $query);
    }

    /**
     * Create a shipment
     *
     * @link https://developers.easyship.com/reference/shipments_create
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(array $payload): ResponseInterface
    {
        $endpoint = sprintf('%s/shipments', self::API_VERSION);

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Retrieve a shipment's details
     *
     * @link https://developers.easyship.com/reference/shipments_show
     * @param string $shipmentId
     * @param array $query Params to pass in the query string
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(string $shipmentId, array $query = []): ResponseInterface
    {
        $endpoint = sprintf(
            '%s/shipments/%s',
            self::API_VERSION,
            $shipmentId
        );

        return $this->easyship->request('get', $endpoint, $query);
    }

    /**
     * Update a shipment
     *
     * @link https://developers.easyship.com/reference/shipments_update
     * @param string $shipmentId
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(string $shipmentId, array $payload): ResponseInterface
    {
        $endpoint = sprintf(
            '%s/shipments/%s',
            self::API_VERSION,
            $shipmentId
        );

        return $this->easyship->request('patch', $endpoint, $payload);
    }

    /**
     * Delete a shipment
     *
     * @link https://developers.easyship.com/reference/shipments_delete
     * @param string $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(string $shipmentId): ResponseInterface
    {
        $endpoint = sprintf('%s/shipments/%s', self::API_VERSION, $shipmentId);

        return $this->easyship->request('delete', $endpoint);
    }

    /**
     * Cancel a shipment that either:
     * - failed to be generated
     * - generated but the shipment is not yet in transit
     *
     * @link https://developers.easyship.com/reference/shipments_cancel
     * @param string $shipmentId
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cancel(string $shipmentId): ResponseInterface
    {
        $endpoint = sprintf(
            '%s/shipments/%s/cancel',
            self::API_VERSION,
            $shipmentId
        );

        return $this->easyship->request('post', $endpoint);
    }

    /**
     * The Trackings API allows you to retrieve the most recent status for a
     * shipment, as well as a history of all previous checkpoints.
     *
     * @link https://developers.easyship.com/reference/shipments_trackings_index
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function trackings(array $query = []): ResponseInterface
    {
        $endpoint = sprintf('%s/shipments/trackings', self::API_VERSION);

        return $this->easyship->request('get', $endpoint, $query);
    }

    /**
     * Update the warehouse state of one or multiple shipments
     * In the API documentation these are categorized under 'eFulfillment'.
     *
     * @link https://developers.easyship.com/reference/efulfillment_warehouse_state_update
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function warehouseStateUpdate(array $payload): ResponseInterface
    {
        $endpoint = sprintf(
            '%s/shipments/warehouse_state_updates',
            self::API_VERSION
        );

        return $this->easyship->request('post', $endpoint, $payload);
    }

    /**
     * Update the tracking number of a shipment and create tracking events.
     * In the API documentation these are categorized under 'eFulfillment'.
     *
     * @link https://developers.easyship.com/reference/efulfillment_tracking_update
     * @param array $payload
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function trackingUpdates(array $payload): ResponseInterface
    {
        $endpoint = sprintf(
            '%s/shipments/tracking_updates',
            self::API_VERSION
        );

        return $this->easyship->request('post', $endpoint, $payload);
    }
}
