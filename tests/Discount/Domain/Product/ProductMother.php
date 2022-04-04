<?php

namespace Kata\Tests\Discount\Domain\Product;

use Kata\Discount\Domain\Product\Product;
use Kata\Tests\Common\Domain\Model\PriceMother;
use Kata\Tests\Shared\Domain\TextMother;

class ProductMother
{
    public static function create(
        ?string $sku = null,
        ?string $name = null,
        ?string $category = null,
        ?string $price = null
    ): Product {
        return Product::create(
            sku: $sku ?? TextMother::create(),
            name: $name ?? TextMother::create(),
            category: $category ?? TextMother::create(),
            price: $price ?? PriceMother::create()->original(),
        );
    }
}
