<?php

namespace Kata\Discount\Domain\Product;

interface ProductDiscounter
{
    public function apply(Product $product): void;
}
