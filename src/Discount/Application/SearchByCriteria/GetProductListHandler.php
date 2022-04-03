<?php

namespace Kata\Discount\Application\SearchByCriteria;

use Kata\Common\Domain\Criteria\{Filters, Order};
use Kata\Discount\Application\ProductsResponse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetProductListHandler
{
    public function __construct(
        private ProductSearcher $searcher,
    ) {
    }

    public function __invoke(GetProductList $query): ProductsResponse
    {
        $filters = Filters::fromValues($query->filters());
        $order = Order::fromValues($query->orderBy(), $query->order());

        return $this->searcher->search(
            $filters,
            $order,
            $query->limit(),
            $query->offset()
        );
    }
}
