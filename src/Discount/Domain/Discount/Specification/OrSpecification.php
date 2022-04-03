<?php

namespace Kata\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Discount;
use Kata\Discount\Domain\Product\Product;

use function Lambdish\Phunctional\map;

final class OrSpecification implements Specification
{
    /** @var Specification[]|null */
    private ?array $specifications;
    private ?array $matchSpecifications = [];

    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(Product $product): bool
    {
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($product)) {
                $this->matchSpecifications[] = $specification;

                return true;
            }
        }

        return false;
    }

    public function discountToApply(): Discount
    {
        $discount = $this->greatestDiscount();

        return new Discount($discount);
    }

    private function greatestDiscount(): ?int
    {
        $discounts = map(
            fn(Specification $specification) => $specification
                ->discountToApply()
                ->amount(),
            $this->matchSpecifications
        );

        return !empty($discounts) ? max($discounts) : null;
    }
}
