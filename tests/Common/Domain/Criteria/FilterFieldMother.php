<?php

declare(strict_types=1);

namespace Kata\Common\Domain\Criteria;

use Kata\Tests\Shared\Domain\Creator;

final class FilterFieldMother
{
    public static function create(?string $fieldName = null): FilterField
    {
        return new FilterField($fieldName ?? Creator::random()->randomElement(['category', 'sku', 'name']));
    }
}
