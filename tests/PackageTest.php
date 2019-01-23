<?php

namespace Tests\PrestaShop\Composer;

use PrestaShop\Composer\Package;
use PHPUnit\Framework\TestCase;

final class PackageTest extends TestCase
{
    public function testCreation()
    {
        $this->assertInstanceOf(Package::class, new Package(
            'owner',
            'name',
            'v1',
            '/tmp/owner/name'
        ));
    }

    public function testCreationUsingHelper()
    {
        $this->assertInstanceOf(Package::class, Package::create(
            'owner/name',
            'v1',
            '/tmp/owner/name'
        ));
    }

    public function testGetName()
    {
        $package = new Package(
            'owner',
            'name',
            'v1',
            '/tmp/owner/name'
        );

        $this->assertSame('name', $package->getName());
    }

    public function testGetOwner()
    {
        $package = new Package(
            'owner',
            'name',
            'v1',
            '/tmp/owner/name'
        );

        $this->assertSame('owner', $package->getOwner());
    }

    public function testGetVersion()
    {
        $package = new Package(
            'owner',
            'name',
            'v1',
            '/tmp/owner/name'
        );

        $this->assertSame('v1', $package->getVersion());
    }

    public function testGetDestination()
    {
        $package = new Package(
            'owner',
            'name',
            'v1',
            '/tmp/owner/name'
        );

        $this->assertSame('/tmp/owner/name', $package->getDestination());
    }

    public function testIsInstalled()
    {
        $package = new Package(
            'owner',
            'name',
            'v1',
            '/tmp/owner/name'
        );

        $this->assertFalse($package->isInstalled());
    }
}
