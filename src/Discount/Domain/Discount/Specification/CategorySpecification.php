<?php

namespace Kata\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Discount;
use Kata\Discount\Domain\Product\Product;
use Kata\Discount\Domain\Product\ProductCategory;

final class CategorySpecification implements Specification
{
    public const DISCOUNT_PERCENTAGE = 30;

    public function __construct(private string $category)
    {
    }

    public function isSatisfiedBy(Product $product): bool
    {
        return $product->category()->equals(ProductCategory::fromString($this->category));
    }

    public function discountToApply(): Discount
    {
        return new Discount(self::DISCOUNT_PERCENTAGE);
    }
}
