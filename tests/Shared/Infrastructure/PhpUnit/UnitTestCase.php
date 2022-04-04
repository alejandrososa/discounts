<?php

namespace Kata\Tests\Shared\Infrastructure\PhpUnit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    private ?Generator $fake = null;

    protected function fake(): Generator
    {
        return $this->fake = $this->fake ?: Factory::create();
    }
}
