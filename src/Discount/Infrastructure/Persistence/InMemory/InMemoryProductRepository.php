<?php

namespace Kata\Discount\Infrastructure\Persistence\InMemory;

use Kata\Common\Domain\Criteria\Criteria;
use Kata\Discount\Domain\Product\{Product, ProductRepository};

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\first;
use function Lambdish\Phunctional\map;

class InMemoryProductRepository implements ProductRepository
{
    public const FILE = 'products.json';

    public function __construct(private InMemoryCriteria $converter, private ReadFile $file)
    {
    }

    public function findAll(): array
    {
        $products = $this->file->content(self::FILE);

        return $this->getData($products);
    }

    public function search(Criteria $criteria): array
    {
        $products = $this->file->content(self::FILE);


        if ($criteria->hasFilters()) {
            $criteriaFilters = $this->converter->convert($criteria);

            $products = first(map(fn($callable) => filter($callable, $products), $criteriaFilters));
        }

        return $this->getData($products);
    }

    private function getData(?array $products): array
    {
        if (empty($products)) {
            return [];
        }

        return \iterator_to_array($this->transform($products));
    }

    private function transform(array $products): \Generator
    {
        foreach ($products as $product) {
            ['sku' => $sku, 'name' => $name, 'category' => $category, 'price' => $price] = $product;
            yield Product::create($sku, $name, $category, $price);
        }
    }
}
