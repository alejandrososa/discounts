<?php

namespace Kata\Tests\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Specification\SpecificationCollection;

class SpecificationCollectionMother
{
    public static function create(?array $specifications = null)
    {
        $rules = $specifications ?? OrSpecificationMother::create();

        return new SpecificationCollection([...$rules]);
    }
}