<?php

namespace Easyship\Tests;

use Faker\Factory as Faker;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    protected function setUp(): void
    {
        $this->faker = Faker::create();
    }
}
