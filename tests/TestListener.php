<?php

namespace Easyship\Tests;

use Easyship\Webhooks\ListenerInterface;

/**
 * This class just exists so that we can create PHPunit mocks of a
 * ListenerInterface implementation, since none are included in the library.
 */
class TestListener implements ListenerInterface
{
    public function fire(array $payload): void
    {
        //
    }
}
