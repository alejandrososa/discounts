<?php

namespace Kata\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Discount;
use Kata\Discount\Domain\Product\Product;

interface Specification
{
    public function isSatisfiedBy(Product $product): bool;

    public function discountToApply(): Discount;
}
