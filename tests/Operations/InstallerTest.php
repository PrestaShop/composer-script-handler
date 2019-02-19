<?php

namespace Tests\PrestaShop\Composer\Operations;

use PrestaShop\Composer\Installer\ModulesInstaller;
use PrestaShop\Composer\Contracts\CommandBuilderInterface;
use Composer\IO\IOInterface;
use PHPUnit\Framework\TestCase;

final class InstallerTest extends TestCase
{
    /**
     * @var IOInterface the IO interface
     */
    private $io;

    /**
     * @var CommandBuilderInterface the commandBuilder
     */
    private $commandBuilder;

    /**
     * @var ConfigurationProcessor
     */
    private $processor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->io = $this->prophesize(IOInterface::class);
        $this->commandBuilder = $this->prophesize(CommandBuilderInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testCreation()
    {
        $this->processor = new ModulesInstaller(
            $this->io->reveal(),
            $this->commandBuilder->reveal(),
            [],
            '/tmp'
        );

        $this->assertInstanceOf(ModulesInstaller::class, $this->processor);
    }
}
