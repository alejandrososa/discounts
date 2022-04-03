<?php

namespace Kata\Discount\Domain\Discount;

use Kata\Discount\Domain\Discount\Specification\SpecificationCollection;
use Kata\Discount\Domain\Product\Product;
use Kata\Discount\Domain\Product\ProductDiscounter;

class Discounter implements ProductDiscounter
{
    public function __construct(private ?SpecificationCollection $collection = null)
    {
    }

    public function apply(Product $product): void
    {
        foreach ($this->collection as $specification) {
            if ($specification->isSatisfiedBy($product)) {
                $amount = $specification->discountToApply()->amount();
                $product->applyDiscount($amount);
            }
        }
    }
}
