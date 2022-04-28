# easyship-php > webhooks

Webhook support is provided by providing the user with a Handler class that
does the following:
1. Accepts an Easyship JWT signature token and payload
2. Verifies that the signature is valid
3. Verifies that the payload contains a documented `event_type`
4. Optionally fires user code before processing the event (webhook.validated)
5. Finally, fires user-defined code associated with the `event_type`

## Usage

Set up a web route in whatever way is appropriate for your application. For
instance in a [Laravel](https://laravel.com) you might create a new
`HttpController` or possibly just assign the route a closure directly in your
`routes/web.api` file. Once you've got a path by which Easyship webhook posts
are making it to your app, you'll need to instantiate an instance of the
Handler and provide it with your secret key that you get from Easyship when
setting up a webhook. The webhook secret will always start with `webh_`.

```php
// A theoretical example in a Laravel routes/web.api file
Route::post('/easyship/webhook', function (\Illuminate\Http\Request $request) {
    $handler = new \Easyship\Webhooks\Handler(
        ...config('easyship.webhook_secrets')
    );
    $handler->handle(
        $request->header('X-EASYSHIP-SIGNATURE'),
        $request->input() // the Request object decoded the JSON in advance
    );
});
```

The webhook handler supports multiple webhook secret keys so that one endpoint
can accept incoming hooks from multiple distinct Easyship accounts. Simply
add each webhook secret as another argument to the constructor.

```php
$handler = new \Easyship\Webhooks\Handler($secretKey1, $secretKey2);
```

Note that the above example demonstrates how you can setup the Handler and
feed webhooks into it. The example would not actually *do* anything, however,
since we didn't attach any listeners to the handler.

To act on the webhook events, you will need to implement the
`Easyship\Webhooks\ListenerInterface`. In the next step, we'll attach the
listener to an event type.

```php
// A conceptual listener implementation that just writes a line to the log
use Easyship\Webhooks\ListenerInterface;

class ShipmentCancelledListener implements ListenerInterface
{
    public function fire(array $payload): void
    {
        error_log("ShipmentCancelledListener was just fired!");
    }
}

```

Finally, you'd need to register this listener with the handler, associating
it with whichever easyship webhook event you choose. It is possible to attach
a listener to more than one type of event (with multiple calls to
`addListener`) and it is also possible to attach numerous listeners to one
event type. When a webhook is received, all listeners will have their fire
methods called, unless one of them kills execution along the way, so be sure
to consider this when implementing your listeners and choosing how to handle
errors/exceptions, etc. The handle method does not catch exceptions thrown
when dispatching to listeners.

```php
// Register the listener to be fired on any shipment.cancelled webhook
Handler::addListener('shipment.cancelled', new ShipmentCancelledListener());
```

### The webhook.validated event

After validating that a signature and the payload validity, the library will
fire a `webhook.validated` event before it dispatches the webhook payload to
any listeners attached to the Easyship `event_type` being handled.

By default, this event will do nothing, but if you choose, you may create a
listener and bind it to the `webhook.validated` event so that your code is
executed at this point in processing. One example of why you might do this
is to immediately return an HTTP 200 header to the Easyship host before the
payload gets run through all of your handlers (this is their suggestion,
and probably isn't necessary unless you're running very heavy synchronous
code through your listeners).

```php
use Easyship\Webhooks\ListenerInterface;

class ReturnOkListener implements ListenerInterface
{
    public function fire(array $payload): void
    {
        header('HTTP/1.0 200 OK');
        flush(); // Send the header immediately
    }
}

// And attach this listener as such
Handler::addListener('webhook.validated', new ReturnOkListener());
```


## Configuration

As per [usage](#usage), the only required configuration is passing a correct
webhook secret key to the `Handler` constructor so that the JWT tokens can be
validated. It is possible to omit the secret key when constructing the
Handler, but if you do this, you **must** override the `JWT` object to be
able to decode the signatures on webhook calls.

### Overriding the Signature Validation Defaults

The JWT token signatures are passed into an object from the `adhocore/jwt`
package, using the default algorithm of HS256 (the only one currently used
by Easyship) and a maximum token age of 1 hour. You can create your own
instance of `\Ahc\Jwt\JWT`, if you want to override these defaults and then
pass it into the handler before it processes the webhook.

```php
// Overriding the JWT handler with one that only allows 15 minute tokens
$jwt = new \Ahc\Jwt\JWT($secretKey, 'HS256', 900);
$handler->setJwtValidator($jwt);
```

## Error Handling

In practice, once your implementation is all working correctly, you should
never run into these exceptions, however it's recommended that you do wrap
your `handle()` call in a try/catch and handle these remote possibilities:

#### Invalid signature

If the signature fails to validate, the `handle()` method will throw a
`Easyship\Exceptions\InvalidSignatureException`.

#### Invalid or missing event_type
If the signature validates but the payload does not contain an `event_type`
documented by Easyship, an instance of
`Easyship\Exceptions\InvalidPayloadException` will throw.
