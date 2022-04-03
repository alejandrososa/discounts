<?php

namespace Kata\Tests\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Specification\CategorySpecification;
use Kata\Discount\Domain\Discount\Specification\OrSpecification;
use Kata\Discount\Domain\Discount\Specification\SkuSpecification;
use Kata\Discount\Domain\Discount\Specification\Specification;

class OrSpecificationMother
{
    public static function create(
        ?Specification $firstSpecification = null,
        ?Specification $secondSpecification = null,
    ): Specification {
        return new OrSpecification(
            $firstSpecification ?? new CategorySpecification('fake'),
            $secondSpecification ?? new SkuSpecification('fake'),
        );
    }
}
