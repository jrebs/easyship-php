<?php

namespace Easyship;

class EasyshipRequest
{
    /**
     * The HTTP method as it will be passed into a Guzzle client
     *
     * @var string
     */
    protected $method;

    /**
     * The complete URI for the request to be made
     *
     * @var string
     */
    protected $uri;

    /**
     * An array of data to be sent with the request.
     *
     * @var array
     */
    protected $payload;

    /**
     * @param string $method
     * @param string $uri
     * @param array $payload
     */
    public function __construct(
        string $method,
        string $uri,
        array $payload
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
