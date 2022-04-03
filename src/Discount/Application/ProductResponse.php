<?php

namespace Kata\Discount\Application;

final class ProductResponse
{
    public function __construct(
        private string $sku,
        private string $name,
        private string $category,
        private array $price,
    ) {
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function price(): array
    {
        return $this->price;
    }
}
