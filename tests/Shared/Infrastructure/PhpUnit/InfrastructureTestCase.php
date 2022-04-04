<?php

namespace Kata\Tests\Shared\Infrastructure\PhpUnit;

use Faker\Factory;
use Faker\Generator;
use Kata\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class InfrastructureTestCase extends KernelTestCase
{
    private ?Generator $fake = null;

    protected function fake(): Generator
    {
        return $this->fake = $this->fake ?: Factory::create();
    }

    protected function setUp(): void
    {
        $_SERVER['KERNEL_CLASS'] = Kernel::class;

        self::bootKernel(['environment' => 'test']);

        parent::setUp();
    }

    protected function service(string $id): mixed
    {
        return $this->getContainer()->get($id);
    }

    protected function parameter($parameter): mixed
    {
        return $this->getContainer()->getParameter($parameter);
    }

    protected function eventually(callable $fn, $totalRetries = 3, $timeToWaitOnErrorInSeconds = 1, $attempt = 0): void
    {
        try {
            $fn();
        } catch (\Throwable $error) {
            if ($totalRetries === $attempt) {
                throw $error;
            }

            sleep($timeToWaitOnErrorInSeconds);

            $this->eventually($fn, $totalRetries, $timeToWaitOnErrorInSeconds, $attempt + 1);
        }
    }
}
