<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;

class CategoriesTest extends TestCase
{
    public function test_lists_categories()
    {
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.easyship.com/2023-01/item_categories');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->categories()->list();
    }
}
