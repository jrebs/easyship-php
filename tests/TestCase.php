<?php

namespace Easyship\Tests;

use Faker\Factory as Faker;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();

        parent::__construct();
    }
}
