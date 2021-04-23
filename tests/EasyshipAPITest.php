<?php

namespace Easyship\Tests;

use Easyship\EasyshipAPI;
use GuzzleHttp\Client;

class EasyshipAPITest extends TestCase
{
    public function test_constructor_requires_api_token()
    {
        $this->expectException(\ArgumentCountError::class);
        $api = new EasyshipAPI();

    }

    public function test_constructor_token_must_be_stringable()
    {
        $this->expectException(\TypeError::class);
        $token = ['test'];
        $api = new EasyshipAPI($token);
    }

    public function test_uri_builder_strips_excess_slashes()
    {
        $api = new EasyshipAPI($this->faker->word);
        $host = '//first/second//';
        $path = '//third//';
        $this->assertEquals(
            '//first/second/third//',
            $api->buildUri($host, $path)
        );
    }

    public function test_builds_an_http_client()
    {
        $api = new EasyshipAPI($this->faker->word);
        $this->assertInstanceOf(
            \GuzzleHttp\Client::class,
            $api->getClient()
        );
    }

    public function test_sets_a_custom_http_client()
    {
        $api = new EasyshipAPI($this->faker->word);
        $client = new Client();
        $client->testProperty = 'test';
        $api->setClient($client);
        $this->assertEquals(
            'test',
            $api->getClient()->testProperty
        );
    }

    public function test_accepts_only_a_compatible_http_client()
    {
        $api = new EasyshipAPI($this->faker->word);
        $exceptionWasThrown = false;
        try {
            $api->setClient(new Client());
        } catch (\Throwable $e) {
            $exceptionWasThrown = true;
        }
        $this->assertFalse($exceptionWasThrown);

        $this->expectException(\TypeError::class);
        $api->setClient(new \stdClass());
    }

    public function test_passes_requests_to_http_client()
    {
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('test', 'https://api.easyship.com/test');
        $api = new EasyshipAPI($this->faker->word);
        $api->setClient($mock);
        $api->request('test', 'test');
    }

    public function test_overrides_api_host()
    {
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('test', 'https://example.com/test');
        $api = new EasyshipAPI($this->faker->word);
        $api->setApiHost('https://example.com/');
        $api->setClient($mock);
        $api->request('test', 'test');
    }

    public function test_merges_request_options()
    {
        $options = [
            'headers' => $this->faker->word,
            'json' => $this->faker->word,
            'test' => $this->faker->word,
            'http_errors' => 'true',
        ];
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('test', 'https://example.com/test', $options);
        $api = new EasyshipAPI($this->faker->word, $options);
        $api->setApiHost('https://example.com/');
        $api->setClient($mock);
        $api->request('test', 'test');
    }


    public function test_non_get_request_payload_goes_to_json()
    {
        $options = [
            'headers' => $this->faker->word,
            'test' => $this->faker->word,
            'http_errors' => 'true',
        ];
        $payload = [$this->faker->word => $this->faker->word];
        $expected = array_merge($options, ['json' => $payload]);
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('post', 'https://api.easyship.com/test', $expected);
        $api = new EasyshipAPI($this->faker->word, $options);
        $api->setClient($mock);
        $api->request('post', 'test', $payload);
    }

    public function test_get_request_payload_goes_to_query()
    {
        $options = [
            'headers' => $this->faker->word,
            'test' => $this->faker->word,
            'http_errors' => 'true',
        ];
        $payload = [$this->faker->word => $this->faker->word];
        $expected = array_merge($options, ['query' => $payload]);
        $mock = $this->createMock(\GuzzleHttp\Client::class);
        $mock->expects($this->once())
            ->method('request')
            ->with('get', 'https://api.easyship.com/test', $expected);
        $api = new EasyshipAPI($this->faker->word, $options);
        $api->setClient($mock);
        $api->request('get', 'test', $payload);
    }

    public function test_gets_categories_module()
    {
        $api = new EasyshipAPI($this->faker->word);
        $this->assertInstanceOf(
            \Easyship\Modules\Categories::class,
            $api->categories()
        );
    }

    public function test_gets_labels_module()
    {
        $api = new EasyshipAPI($this->faker->word);
        $this->assertInstanceOf(
            \Easyship\Modules\Labels::class,
            $api->labels()
        );
    }

    public function test_gets_pickups_module()
    {
        $api = new EasyshipAPI($this->faker->word);
        $this->assertInstanceOf(
            \Easyship\Modules\Pickups::class,
            $api->pickups()
        );
    }

    public function test_gets_rates_module()
    {
        $api = new EasyshipAPI($this->faker->word);
        $this->assertInstanceOf(
            \Easyship\Modules\Rates::class,
            $api->rates()
        );
    }

    public function test_gets_shipments_module()
    {
        $api = new EasyshipAPI($this->faker->word);
        $this->assertInstanceOf(
            \Easyship\Modules\Shipments::class,
            $api->shipments()
        );
    }

    public function test_gets_tracking_module()
    {
        $api = new EasyshipAPI($this->faker->word);
        $this->assertInstanceOf(
            \Easyship\Modules\Tracking::class,
            $api->tracking()
        );
    }
}
