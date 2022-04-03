<?php

namespace Kata\Discount\Infrastructure\Persistence\InMemory;

use Kata\Common\Domain\Criteria\Criteria;
use Kata\Common\Domain\Criteria\Filter;
use Kata\Common\Domain\Criteria\FilterOperator;

final class InMemoryCriteriaConverter implements InMemoryCriteria
{
    private ?array $filters = [];

    public function convert(Criteria $criteria): array
    {
        /** @var Filter $filter */
        foreach ($criteria->filters() as $filter) {
            $field = $filter->field()->value();
            $value = $filter->value()->value();

            $match = match ($filter->operator()->value()) {
                FilterOperator::EQUAL, FilterOperator::CONTAINS => function ($item) use ($field, $value) {
                    return !empty($item[$field]) && $item[$field] == $value;
                },
                FilterOperator::NOT_EQUAL, FilterOperator::NOT_CONTAINS => function ($item) use ($field, $value) {
                    return !empty($item[$field]) && $item[$field] != $value;
                },
                FilterOperator::GT => fn() => function ($item) use ($field, $value) {
                    return !empty($item[$field]) && $item[$field] > $value;
                },
                FilterOperator::LT => function ($item) use ($field, $value) {
                    return !empty($item[$field]) && $item[$field] < $value;
                },
            };

            $this->addFilter($match);
        }

        return $this->filters;
    }

    private function addFilter(?callable $fn = null): void
    {
        if (!empty($fn)) {
            $this->filters[] = $fn;
        }
    }

    private function formatSort(Criteria $criteria): array
    {
        //@todo this will be done, but not today
        return [];
    }
}