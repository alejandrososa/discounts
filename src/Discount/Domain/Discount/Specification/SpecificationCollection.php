<?php

namespace Kata\Discount\Domain\Discount\Specification;

use Kata\Common\Domain\Collection;

class SpecificationCollection extends Collection
{
    public function __construct(iterable $specifications)
    {
        parent::__construct([...$specifications]);
    }

    protected function type(): string
    {
        return Specification::class;
    }
}
