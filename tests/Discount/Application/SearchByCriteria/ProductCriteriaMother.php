<?php

namespace Kata\Tests\Discount\Application\SearchByCriteria;

use Kata\Common\Domain\Criteria\Criteria;
use Kata\Tests\Common\Domain\Criteria\CriteriaMother;
use Kata\Tests\Common\Domain\Criteria\FilterMother;
use Kata\Tests\Common\Domain\Criteria\FiltersMother;

final class ProductCriteriaMother
{
    public static function create(
        ?string $field = null,
        ?string $value = null
    ): Criteria {
        return CriteriaMother::create(
            FiltersMother::createOne(
                FilterMother::fromValues(
                    [
                        'field' => $field ?? 'name',
                        'operator' => 'CONTAINS',
                        'value' => $value ?? 'fake',
                    ]
                )
            )
        );
    }

    public static function nameContains(string $text): Criteria
    {
        return self::create(field: 'name', value: $text);
    }

    public static function skuContains(string $text): Criteria
    {
        return self::create(field: 'sku', value: $text);
    }

    public static function categoryContains(string $text): Criteria
    {
        return self::create(field: 'category', value: $text);
    }
}
