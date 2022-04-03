<?php

namespace Kata\Tests\Discount\Application\SearchByCriteria;

use Kata\Discount\Application\ProductsResponse;
use Kata\Discount\Application\SearchByCriteria\ProductSearcher;
use Kata\Tests\Common\Domain\Criteria\CriteriaMother;
use Kata\Tests\Discount\DiscountInfrastructureTestCase;
use Kata\Tests\Discount\Domain\Product\ProductMother;

class ProductSearcherTest extends DiscountInfrastructureTestCase
{
    private ?ProductSearcher $sut = null;

    protected function setUp(): void
    {
        $this->sut = new ProductSearcher(
            $this->productRepository(),
            $this->productDiscounter()
        );
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    public function testItShouldSearchAllExistingProductsWithAnEmptyCriteriaAndReturnProductsResponse()
    {
        $existingProduct = ProductMother::create();
        $anotherExistingProduct = ProductMother::create();
        $existingProducts = [$existingProduct, $anotherExistingProduct];

        $criteria = CriteriaMother::empty();
        $this->productRepositoryMustReturn($existingProducts);

        $result = $this->sut->search(
            filters: $criteria->filters(),
            order: $criteria->order(),
            limit: $criteria->limit(),
            offset: $criteria->offset()
        );

        $this->eventually(fn() => $this->assertInstanceOf(ProductsResponse::class, $result));
        $this->eventually(fn() => $this->assertCount(count($existingProducts), $result->products()));
    }

    public function testItShouldSearchAllExistingProductsWithCriteriaAndReturnProductsResponse()
    {
        $existingProduct = ProductMother::create(name: 'existing', category: 'boots');
        $lastExistingProduct = ProductMother::create(name: 'last', category: 'boots');
        $expectedProducts = [$existingProduct, $lastExistingProduct];

        $criteria = ProductCriteriaMother::categoryContains('boots');
        $this->productRepositoryMustReturn($expectedProducts);

        $result = $this->sut->search(
            filters: $criteria->filters(),
            order: $criteria->order(),
            limit: $criteria->limit(),
            offset: $criteria->offset()
        );

        $this->eventually(fn() => $this->assertInstanceOf(ProductsResponse::class, $result));
        $this->eventually(fn() => $this->assertCount(count($expectedProducts), $result->products()));
        $this->eventually(fn() => $this->assertEquals('existing', $result->products()[0]->name()));
        $this->eventually(fn() => $this->assertEquals('last', $result->products()[1]->name()));
    }
}
