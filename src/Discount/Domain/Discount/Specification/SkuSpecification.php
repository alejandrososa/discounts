<?php

namespace Kata\Discount\Domain\Discount\Specification;

use Kata\Common\Domain\Model\Sku;
use Kata\Discount\Domain\Discount\Discount;
use Kata\Discount\Domain\Product\Product;

final class SkuSpecification implements Specification
{
    public const DISCOUNT_PERCENTAGE = 15;

    public function __construct(private string $sku)
    {
    }

    public function isSatisfiedBy(Product $product): bool
    {
        return $product->sku()->equals(Sku::fromString($this->sku));
    }

    public function discountToApply(): Discount
    {
        return new Discount(self::DISCOUNT_PERCENTAGE);
    }
}
