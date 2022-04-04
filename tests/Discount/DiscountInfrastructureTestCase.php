<?php

namespace Kata\Tests\Discount;

use Kata\Discount\Domain\Product\Product;
use Kata\Discount\Domain\Product\ProductDiscounter;
use Kata\Discount\Domain\Product\ProductRepository;
use Kata\Discount\Infrastructure\Persistence\InMemory\InMemoryCriteria;
use Kata\Discount\Infrastructure\Persistence\InMemory\ReadFile;
use Kata\Tests\Shared\Infrastructure\PhpUnit\InfrastructureTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function Lambdish\Phunctional\map;

abstract class DiscountInfrastructureTestCase extends InfrastructureTestCase
{
    private ProductRepository|MockObject|null $productRepository;
    private ProductDiscounter|MockObject|null $productDiscounter;
    private InMemoryCriteria|MockObject|null $inMemoryCriteria;
    private ReadFile|MockObject|null $readFile;

    protected function productRepository(): ProductRepository|MockObject
    {
        return $this->productRepository = $this->productRepository ?? $this->createMock(ProductRepository::class);
    }

    protected function productDiscounter(): ProductDiscounter|MockObject
    {
        return $this->productDiscounter = $this->productDiscounter ?? $this->createMock(ProductDiscounter::class);
    }

    protected function inMemoryCriteria(): InMemoryCriteria|MockObject
    {
        return $this->inMemoryCriteria = $this->inMemoryCriteria ?? $this->createMock(InMemoryCriteria::class);
    }

    protected function readFile(): ReadFile|MockObject
    {
        return $this->readFile = $this->readFile ?? $this->createMock(ReadFile::class);
    }

    /**
     * @param Product[] $existingProducts
     * @return void
     */
    protected function readFileMustReturn(array $existingProducts): void
    {
        $existingItems = map(fn($product) => [
            'sku' => (string)$product->sku(),
            'name' => (string)$product->name(),
            'category' => (string)$product->category(),
            'price' => $product->price()->original(),
        ], $existingProducts);

        $this->readFile()
            ->method('content')
            ->willReturn($existingItems);
    }

    protected function productRepositoryMustReturn(array $existingProducts): void
    {
        $this->productRepository()
            ->method('search')
            ->willReturn($existingProducts);
    }
}
