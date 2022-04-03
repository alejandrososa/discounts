<?php

namespace Kata\Discount\Domain\Product;

use Kata\Common\Domain\Contracts\Entity;
use Kata\Common\Domain\Contracts\Equatable;
use Kata\Common\Domain\Model\Currency;
use Kata\Common\Domain\Model\Price;
use Kata\Common\Domain\Model\Sku;

class Product implements Entity
{
    private function __construct(
        private Sku $sku,
        private ProductName $name,
        private ProductCategory $category,
        private Price $price
    ) {
    }

    public function sku(): Sku
    {
        return $this->sku;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function category(): ProductCategory
    {
        return $this->category;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function equals(Equatable $other): bool
    {
        return static::class === $other::class
            && $this->sku()->value() === $other->sku()->value();
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku->value(),
            'name' => $this->name->value(),
            'category' => $this->category->value(),
            'price' => $this->price,
        ];
    }

    public static function create(
        string $sku,
        string $name,
        string $category,
        int $price
    ): self {
        return new self(
            sku: Sku::fromString($sku),
            name: ProductName::fromString($name),
            category: ProductCategory::fromString($category),
            price: Price::fromInt($price, new Currency())
        );
    }

    public function applyDiscount(int $discount): self
    {
        $this->price = $this->price->applyPercentDiscount($discount);

        return $this;
    }
}
