<?php

namespace Easyship\Modules;

use Easyship\Module;
use Psr\Http\Message\ResponseInterface;

class Couriers extends Module
{
    /**
     * Returns a list of couriers that are available with your account.
     *
     * @link https://developers.easyship.com/reference/couriers_index
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(array $query = []): ResponseInterface
    {
        $endpoint = sprintf('%s/couriers', self::API_VERSION);

        return $this->easyship->request('get', $endpoint, $query);
    }

    /**
     * Returns a list of pickup slots in local time for the coming 7 days.
     *
     * @link https://developers.easyship.com/reference/couriers_pickup_slots_index
     * @param string $courierId
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listSlots(string $courierId, array $query): ResponseInterface
    {
        $endpoint = sprintf(
            '%s/couriers/%s/pickup_slots',
            self::API_VERSION,
            $courierId
        );

        return $this->easyship->request('get', $endpoint, $query);
    }
}
