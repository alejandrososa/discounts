<?php

namespace Kata\Tests\Common\Domain\Model;

use Kata\Common\Domain\Model\Sku;
use Kata\Tests\Common\CommonUnitTestCase;

class SkuTest extends CommonUnitTestCase
{
    private ?string $sku = null;

    protected function setUp(): void
    {
        $this->sku = $this->fake()->postcode();
    }

    protected function tearDown(): void
    {
        $this->sku = null;
    }

    public function testItShouldCreateSkuFromString()
    {
        $sku = Sku::fromString($this->sku);

        $this->assertSame($this->sku, $sku->value());
        $this->assertSame($this->sku, (string)$sku);
    }

    /**
     * @test
     * @depends testItShouldCreateSkuFromString
     */
    public function testItCanBeCompared()
    {
        $anotherSku = $this->fake()->postcode();

        $first = Sku::fromString($this->sku);
        $second = Sku::fromString($this->sku);
        $third = Sku::fromString($anotherSku);

        $this->assertTrue($first->equals($second));
        $this->assertFalse($first->equals($third));
    }
}
