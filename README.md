# easyship-php

[![codecov](https://codecov.io/gh/jrebs/easyship-php/branch/master/graph/badge.svg?token=OK5HAPJ1YZ)](https://codecov.io/gh/jrebs/easyship-php)

A PHP library to make PHP calls to the [Easyship](https://www.easyship.com)
API. This library just wraps some of the repetitive/ugly work like creating
an HTTP client and building the requests. The end user will just need to
assemble arrays of the appropriate payload data and then evaluate the
response.

#### API Version

Presently, only API version `v1` is supported, as the `v2` API is in beta
and still not complete. Once `v2` is ready for production use, I'll expand
this library to support that version as well.

* [Installation](#installation)
* [Usage](#usage)
* [Configuration](#configuration)
* [Roadmap](#roadmap)
* [Support](#support)
* [License](#license)

## Installation

Install with [composer](https://getcomposer.org).
```
composer require  --no-dev jrebs/easyship-php
```

If you omit the `--no-dev` argument, then you will also get `phpunit` and
`fakerphp` installed for running the provided unit tests.

# Usage

```php
// Create the EasyshipAPI object
$token = getenv('EASYSHIP_TOKEN');
$api = new Easyship\EasyshipAPI($token);

// Get a list of categories
$response = $api->categories()->list();

// Get a shipment
$response = $api->shipments()->get('ESTEST10001');

// Buy a label for a shipment
$response = $api->labels()->buy(['easyship_shipment_id' => 'ESTEST10001']);
```

All methods return an instance of an object implementing
`Psr\Http\Message\ResponseInterface`, typically `GuzzleHttp\Psr7\Response`,
which can be used as needed [see the Guzzle documentation for more](https://docs.guzzlephp.org/en/stable/quickstart.html#using-responses).

By default, all of the calls are made using the request option
`'http_errors' => true`, which means that exceptions will be thrown if any
requests fail. The `EasyshipAPI::request()` method docblock shows which
exceptions you can expect to be thrown. The library allows all exceptions to
bubble up to the application so that the developer can choose how to handle
them at implementation. You can override this option in the options array
passed into the `EasyshipAPI` constructor, if you prefer.

```php
/**
 * @throws \GuzzleHttp\Exception\ConnectException on network error
 * @throws \GuzzleHttp\Exception\ClientException on 400-level errors
 * @throws \GuzzleHttp\Exception\ServerException on 500-level errors
 */
```


## Configuration

The only configuration requirement is an API access token, which you'll
get from your easyship account interface. If you haven't made one yet, go
to https://app.easyship.com/connect and look for `API Integration` near the
bottom of the list.

For each integration you create, there will be two access tokens created, one
that starts with `prod_` and one that starts with `sand_`. The latter is your
sandbox key and is the one you should use for testing and developing your
integrations. It uses the same live endpoints but works off a separate set of
test-only data.

#### Providing Request Options to the HTTP client

The `EasyshipAPI` constructor accepts an array of request options that will
be merged into the options that are passed to the HTTP client when requests
are sent to the API endpoints. See the
[guzzle request options](https://docs.guzzlephp.org/en/stable/request-options.html) documentation for possibilities.

```php
// Pass custom options that will be used by the Guzzle client
$api = new Easyship\EasyshipAPI($token, [
    'verify' => false, // don't verify ssl certificate
    'connect_timeout' => 30, // wait maximum 30 seconds before giving up
]);
```

#### Overriding the API host

For testing/development you may want to override the target host so that the
calls you submit will be sent to your own server for inspection.

```php
// Force all the calls from this API object to a localhost server
$api->setApiHost('http://localhost:8080/');
```

## Roadmap

* **Coming Soon**
  * supplemental package to for integrating into [Laravel](https://laravel.com) applications
  * add classes to handle validating and dispatching Easyship webhooks
* **TBD**
  * support for API `v2` once it is ready for production use.
  * possibly support other HTTP clients by coding against a PSR interface
  instead of the Guzzle client directly

## Support

If you're getting unexpected results from your API calls, the most likely
case is that your payload is not valid and you'll want to consult the
[Easyship reference docs](https://developers.easyship.com/v1.0/reference).
Note that you can plug values into the forms on that page, including your
sandbox token, and run the tests from the interface. If you find an issue
where the exact same call is working in Easyship's test page but failing
with this library, please raise an issue with a very detailed explanation
of the problem and include a copy of the exact code being run (at least
the payload of test data being passed in) so that the issue can be easily
reproduced. Please also include a copy of the incorrect response you're
getting back.

## License

This software was written by me, [Justin Rebelo](https://github.com/jrebs),
and is released under the [MIT license](LICENSE.md).
