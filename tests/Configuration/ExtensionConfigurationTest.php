<?php

namespace Tests\PrestaShop\Composer\Installer;

use PrestaShop\Composer\Configuration\ExtensionConfiguration;
use PHPUnit\Framework\TestCase;

final class ExtensionConfigurationTest extends TestCase
{
    public function testCreation()
    {
        $configuration = new ExtensionConfiguration([]);

        $this->assertInstanceOf(ExtensionConfiguration::class, $configuration);
    }
}
