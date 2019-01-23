<?php

namespace Tests\PrestaShop\Composer;

use PrestaShop\Composer\ConfigurationProcessor;
use PrestaShop\Composer\Contracts\ExecutorInterface;
use Composer\IO\IOInterface;
use PHPUnit\Framework\TestCase;

class ConfigurationProcessorTest extends TestCase
{
    /**
     * @var IOInterface the IO interface
     */
    private $io;

    /**
     * @var ExecutorInterface the process executor
     */
    private $executor;

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
        $this->executor = $this->prophesize(ExecutorInterface::class);
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
            $this->executor->reveal(),
            '/tmp'
        );

        $this->assertInstanceOf(ConfigurationProcessor::class, $this->processor);
    }
}
