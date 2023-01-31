# easyship-php

[![codecov](https://codecov.io/gh/jrebs/easyship-php/branch/master/graph/badge.svg?token=OK5HAPJ1YZ)](https://codecov.io/gh/jrebs/easyship-php)
[![License](https://img.shields.io/packagist/l/jrebs/easyship-php.svg?style=flat-square)](https://packagist.org/packages/jrebs/easyship-php)

A PHP library to make PHP calls to the [Easyship](https://www.easyship.com)
API. This library wraps some of the repetitive/ugly work like creating
an HTTP client, building and sending requests. The end user will just need to
assemble arrays of the appropriate payload data and then evaluate the
responses.

This package comprises two distinct sets of functionality. The first is the
API communication component, which allows the user to easily write code for
sending outbound API calls to the Easyship API. The second is support for
receiving inbound webhook posts from Easyship, allowing the user to easily
pass those calls into a dispatching handler and have their payloads passed
off to custom code attached to the handler using listeners. Webhooks are an
optional feature of Easyship's API and there's you can completely ignore them
if it's not part of your implementation plan.

#### API Version

Until `v1.4`, this library only supported the Easyship API `v1`. `v2` was
never supported because for the longest time it was incomplete and marked
unstable. Since `v1.4` of this library, `v2023-01` is the only supported
version. You can just install an earlier build if you need to use old calls
for some reason.

* [Installation](#installation)
* [Usage](#usage)
* [Configuration](#configuration)
* [Webhooks](#webhooks)
* [Roadmap](#roadmap)
* [Support](#support)
* [License](#license)

## Installation

Unless using [Laravel](https://laravel.com), install with
[composer](https://getcomposer.org) like normal:

```sh
composer require jrebs/easyship-php
```

If using the library in a [Laravel](https://laravel.com) application, then
you'll probably find it more convenient to install the companion package,
[easyship-laravel](https://github.com/jrebs/easyship-laravel) (which will
also require this package as a dependency).

In this case, instead run:
```sh
composer require jrebs/easyship-laravel
```
See the [easyship-laravel](https://github.com/jrebs/easyship-laravel) page
for documentation specific to this method.

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
$response = $api->labels()->request(['easyship_shipment_id' => 'ESTEST10001']);
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
Of course, if you're using another PSR7-compatible client, then you'll
presumably get some exception based on `\RuntimeException`. Using other
clients isn't fully tested but in theory should work.

## Configuration

Typically the only thing you need is to configure an api key, which you'll
get from your easyship account interface. If you haven't made one yet, go
to https://app.easyship.com/connect and look for `API Integration` near the
bottom of the list.

For each integration you create, there will be two access tokens created, one
that starts with `prod_` and one that starts with `sand_`. The latter is your
sandbox key and is the one you should use for testing and developing your
integrations. It uses the same live endpoints but works off a separate set of
test-only data.

The apiToken on the EasyshipAPI is optional, as a single customer may often
be making api calls with different tokens.

#### Providing Request Options to the HTTP client

The `EasyshipAPI` constructor accepts an array of request options that will
be merged into the options that are passed to the HTTP client when requests
are sent to the API endpoints. See the
[guzzle request options](https://docs.guzzlephp.org/en/stable/request-options.html) documentation for possibilities.

```php
// Pass custom options that will be used by the client
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

## Webhooks

See [WEBHOOKS.md](WEBHOOKS.md).

## Roadmap

* More complete/useful testing.

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
