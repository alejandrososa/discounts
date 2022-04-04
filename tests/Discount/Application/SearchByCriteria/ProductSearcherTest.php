<?php

namespace Kata\Tests\Discount\Application\SearchByCriteria;

use Kata\Discount\Application\ProductsResponse;
use Kata\Discount\Application\SearchByCriteria\ProductSearcher;
use Kata\Discount\Infrastructure\Persistence\InMemory\InMemoryCriteriaConverter;
use Kata\Discount\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use Kata\Tests\Common\Domain\Criteria\CriteriaMother;
use Kata\Tests\Discount\DiscountInfrastructureTestCase;
use Kata\Tests\Discount\Domain\Product\ProductMother;

class ProductSearcherTest extends DiscountInfrastructureTestCase
{
    private ?ProductSearcher $sut = null;

    protected function setUp(): void
    {
        $this->sut = new ProductSearcher(
            new InMemoryProductRepository(
                new InMemoryCriteriaConverter(),
                $this->readFile(),
            ),
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
        $lastExistingProduct = ProductMother::create();
        $existingProducts = [$existingProduct, $anotherExistingProduct, $lastExistingProduct];

        $criteria = CriteriaMother::empty();
        $this->readFileMustReturn($existingProducts);

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
        $anotherExistingProduct = ProductMother::create();
        $oneMoreExistingProduct = ProductMother::create();
        $existingProducts = [$existingProduct, $anotherExistingProduct, $lastExistingProduct, $oneMoreExistingProduct];
        $expectedProductsMatchCriteria = [$existingProduct, $lastExistingProduct];

        $criteria = ProductCriteriaMother::categoryContains('boots');
        $this->readFileMustReturn($existingProducts);

        $result = $this->sut->search(
            filters: $criteria->filters(),
            order: $criteria->order(),
            limit: $criteria->limit(),
            offset: $criteria->offset()
        );

        $this->eventually(fn() => $this->assertInstanceOf(ProductsResponse::class, $result));
        $this->eventually(fn() => $this->assertCount(count($expectedProductsMatchCriteria), $result->products()));
        $this->eventually(fn() => $this->assertEquals('existing', $result->products()[0]->name()));
        $this->eventually(fn() => $this->assertEquals('last', $result->products()[1]->name()));
    }
}
