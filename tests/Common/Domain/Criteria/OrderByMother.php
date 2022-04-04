<?php

namespace Kata\Tests\Common\Domain\Criteria;

use Kata\Common\Domain\Criteria\OrderBy;
use Kata\Tests\Shared\Domain\Creator;

final class OrderByMother
{
    public static function create(?string $fieldName = null): OrderBy
    {
        return new OrderBy($fieldName ?? Creator::random()->randomElement(['category', 'sku', 'name']));
    }
}
