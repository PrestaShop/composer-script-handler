<?php

namespace Tests\PrestaShop\Composer\Exceptions;

use PrestaShop\Composer\Exceptions\InvalidPackageException;
use PHPUnit\Framework\TestCase;

final class InvalidPackageExceptionTest extends TestCase
{
    public function testCreation()
    {
        $this->assertInstanceOf(
            InvalidPackageException::class,
            new InvalidPackageException('awesome')
        );
    }

    public function testExceptionMessageIsRight()
    {
        $exception = new InvalidPackageException('foo:1.2.3');

        $this->assertSame(
            'Expected expression like prestashop/some_module:1.0, got "foo:1.2.3"',
            $exception->getMessage()
        );
    }
}
