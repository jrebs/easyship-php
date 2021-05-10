<?php

namespace Easyship;

use Easyship\Modules\Categories;
use Easyship\Modules\Labels;
use Easyship\Modules\Pickups;
use Easyship\Modules\Rates;
use Easyship\Modules\Shipments;
use Easyship\Modules\Tracking;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class EasyshipAPI
{
    /**
     * @var string
     */
    protected $apiToken;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $apiHost = 'https://api.easyship.com';

    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    protected $client;

    /**
     * @link https://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @param string $apiToken
     * @param array $options An array of request options to be merged in
     */
    public function __construct(string $apiToken = null, array $options = [])
    {
        $this->apiToken = $apiToken;
        $this->options = $options;
    }

    /**
     * Send a request to the Easyship API and return a ResponseInterface
     * object. If the request is a GET request, any payload data will be
     * built into a query string, otherwise it will be sent as JSON content.
     *
     * @param string $method The HTTP method to be used
     * @param string $endpoint Full path to request excluding the host
     * @param array $payload An array of params
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\ConnectException on network error
     * @throws \GuzzleHttp\Exception\ClientException on 400-level errors
     * @throws \GuzzleHttp\Exception\ServerException on 500-level errors
     */
    public function request(
        string $method,
        string $endpoint,
        array $payload = []
    ): ResponseInterface {
        $uri = $this->buildUri($this->apiHost, $endpoint);
        $request = $this->buildRequest($method, $uri, $payload);

        return $this->submitRequest($request);
    }

    public function buildRequest(
        string $method,
        string $uri,
        array $options = []
    ): EasyshipRequest {
        return new EasyshipRequest($method, $uri, $options);
    }

    /**
     * Pass a request into the Guzzle client.
     *
     * @param EasyshipRequest $request
     * @return \GuzzleHttp\Psr7\Response
     */
    public function submitRequest(EasyshipRequest $request): ResponseInterface
    {
        $uri = $request->getUri();
        $options = array_merge(
            [
                'headers' => [
                'Authorization' => "Bearer {$this->apiToken}",
                'Content-Type' => 'application/json',
                ],
                'http_errors' => true,
            ],
            $this->options
        );
        if ($request->getPayload()) {
            if (strtolower($request->getMethod()) == 'get') {
                $uri .= '?' . http_build_query($request->getPayload());
            } else {
                $options['json'] = $request->getPayload();
            }
        }

        return $this->getClient()->request(
            $request->getMethod(),
            $uri,
            $options
        );
    }

    /**
     * Join the host and path to make a full URI for the API request,
     * ensuring there is one (and only one) slash joining them.
     *
     * @param string $host
     * @param string $path
     *
     * @return string
     */
    public function buildUri(string $host, string $path): string
    {
        return sprintf(
            '%s/%s',
            rtrim($host, '/'),
            ltrim($path, '/')
        );
    }

    /**
     * Override the default Easyship API host for development/testing.
     *
     * @param string $apiHost
     *
     * @return void
     */
    public function setApiHost(string $apiHost): void
    {
        $this->apiHost = $apiHost;
    }

    /**
     * Update the api token used by this object.
     *
     * @param string $apiToken
     * @return void
     */
    public function setApiToken(string $apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    /**
     * Get an Http client to send API requests with. If none is already
     * available, an instance of GuzzleHttp/Client will be created.
     *
     * @return \Psr\Http\Client\ClientInterface
     */
    public function getClient(): ClientInterface
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * Pass in a compatible HTTP client object to be used.
     *
     * @param \Psr\Http\Client\ClientInterface $client
     *
     * @return void
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * @return \Easyship\Modules\Categories
     */
    public function categories(): Categories
    {
        return new Categories($this);
    }

    /**
     * @return \Easyship\Modules\Labels
     */
    public function labels(): Labels
    {
        return new Labels($this);
    }

    /**
     * @return \Easyship\Modules\Pickups
     */
    public function pickups(): Pickups
    {
        return new Pickups($this);
    }

    /**
     * @return \Easyship\Modules\Rates
     */
    public function rates(): Rates
    {
        return new Rates($this);
    }

    /**
     * @return \Easyship\Modules\Shipments
     */
    public function shipments(): Shipments
    {
        return new Shipments($this);
    }

    /**
     * @return \Easyship\Modules\Tracking
     */
    public function tracking(): Tracking
    {
        return new Tracking($this);
    }
}
