<?php

namespace Kata\Tests\Discount\Domain\Product;

use Kata\Discount\Domain\Product\Product;

class ProductMother
{
    public static function create(
        ?string $sku = null,
        ?string $name = null,
        ?string $category = null,
        ?string $price = null
    ): Product {
        return Product::create(
            sku: $sku ?? md5('sku'.random_int(1,100)),
            name: $name ?? md5('name'.random_int(1,100)),
            category: $category ?? md5('category'.random_int(1,100)),
            price: $price ?? random_int(1, 1000),
        );
    }
}
