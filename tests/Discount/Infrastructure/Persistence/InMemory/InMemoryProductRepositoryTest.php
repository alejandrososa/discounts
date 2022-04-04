<?php

namespace Kata\Tests\Discount\Infrastructure\Persistence\InMemory;

use Kata\Discount\Domain\Product\ProductRepository;
use Kata\Discount\Infrastructure\Persistence\InMemory\InMemoryCriteriaConverter;
use Kata\Discount\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use Kata\Tests\Common\Domain\Criteria\CriteriaMother;
use Kata\Tests\Discount\Application\SearchByCriteria\ProductCriteriaMother;
use Kata\Tests\Discount\DiscountInfrastructureTestCase;
use Kata\Tests\Discount\Domain\Product\ProductMother;
use Kata\Tests\Shared\Domain\TextMother;

class InMemoryProductRepositoryTest extends DiscountInfrastructureTestCase
{
    private ?ProductRepository $sut = null;

    protected function setUp(): void
    {
        $this->sut = new InMemoryProductRepository(
            new InMemoryCriteriaConverter(),
            $this->readFile()
        );
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    public function testsItShouldSearchAllExistingProducts(): void
    {
        $existingProduct = ProductMother::create();
        $anotherExistingProduct = ProductMother::create();
        $existingProducts = [$existingProduct, $anotherExistingProduct];

        $this->readFileMustReturn($existingProducts);

        $this->eventually(fn() => $this->assertEquals($existingProducts, $this->sut->findAll()));
    }

    public function testItShouldSearchAllExistingProductsWithAnEmptyCriteria(): void
    {
        $existingProduct = ProductMother::create();
        $anotherExistingProduct = ProductMother::create();
        $existingProducts = [$existingProduct, $anotherExistingProduct];

        $this->readFileMustReturn($existingProducts);

        $this->eventually(
            fn() => $this->assertEquals($existingProducts, $this->sut->search(CriteriaMother::empty()))
        );
    }

    public function testItShouldFilterByCriteria(): void
    {
        $sku = TextMother::create();
        $existingProduct = ProductMother::create(sku: $sku);
        $anotherExistingProduct = ProductMother::create();
        $lastExistingProduct = ProductMother::create();
        $existingProducts = [$existingProduct, $anotherExistingProduct, $lastExistingProduct];
        $expectedProducts = [$existingProduct];

        $nameContainsDddCriteria = ProductCriteriaMother::skuContains($sku);

        $this->readFileMustReturn($existingProducts);

        $this->eventually(
            fn() => $this->assertEquals($expectedProducts, $this->sut->search($nameContainsDddCriteria))
        );
    }
}
