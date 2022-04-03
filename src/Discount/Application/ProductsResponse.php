<?php

namespace Kata\Discount\Application;

final class ProductsResponse
{
    private array $products;

    public function __construct(ProductResponse ...$products)
    {
        $this->products = $products;
    }

    public function products(): array
    {
        return $this->products;
    }
}
