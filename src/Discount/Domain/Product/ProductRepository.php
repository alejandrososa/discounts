<?php

namespace Kata\Discount\Domain\Product;

use Kata\Common\Domain\Criteria\Criteria;

interface ProductRepository
{
    public function findAll(): array;

    public function search(Criteria $criteria): array;
}
