<?php

namespace Tests\PrestaShop\Composer;

use PrestaShop\Composer\ConfigurationProcessor;
use PrestaShop\Composer\Contracts\CommandBuilderInterface;
use Composer\IO\IOInterface;
use PHPUnit\Framework\TestCase;

final class ConfigurationProcessorTest extends TestCase
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
        $this->processor = new ConfigurationProcessor(
            $this->io->reveal(),
            $this->commandBuilder->reveal(),
            '/tmp'
        );

        $this->assertInstanceOf(ConfigurationProcessor::class, $this->processor);
    }
}
