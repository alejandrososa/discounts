<?php

namespace Kata\Discount\Application\SearchByCriteria;

use Kata\Common\Domain\Criteria\{Criteria, Filters, Order};
use Kata\Discount\Application\{ProductResponse, ProductsResponse};
use Kata\Discount\Domain\Product\{Product, ProductDiscounter, ProductRepository};

use function Lambdish\Phunctional\map;

final class ProductSearcher
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductDiscounter $discounter
    ) {
    }

    public function search(Filters $filters, Order $order, ?int $limit, ?int $offset): ProductsResponse
    {
        //first step: find products by criteria
        $criteria = new Criteria($filters, $order, $offset, $limit);
        $products = $this->productRepository->search($criteria);

        //second step: apply discount when necessary
        foreach ($products as $product) {
            $this->discounter->apply($product);
        }

        //last one: create response
        return new ProductsResponse(...map($this->toResponse(), $products));
    }

    private function toResponse(): callable
    {
        return static fn(Product $product) => new ProductResponse(
            $product->sku()->value(),
            $product->name()->value(),
            $product->category()->value(),
            $product->price()->toArray(),
        );
    }
}
