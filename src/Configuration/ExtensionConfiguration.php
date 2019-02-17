<?php

namespace PrestaShop\Composer\Configuration;

use PrestaShop\Composer\Contracts\ConfigurationInterface;

/**
 * Simple value object to store the Composer extension configuration
 */
final class ExtensionConfiguration implements ConfigurationInterface
{
    /**
     * @var int the Process update frequency
     */
    private $updateFrequency;

    /**
     * @var int the number of running processes during operations
     */
    private $processes;

    /**
     * @var array the list of modules to manage
     */
    private $modules;

    /**
     * @var bool checks whether or not we overwrite
     *           existing modules
     */
    private $allowOverwriting;

    public function __construct(array $composerConfiguration)
    {
        $this->modules = isset($composerConfiguration['modules']) ?
            $composerConfiguration['modules'] :
            []
        ;

        $this->updateFrequency = isset($configuration['update-frequency']) ?
            $configuration['update-frequency'] :
            self::DEFAULT_PROCESS_UPDATE_FREQUENCY
        ;

        $this->processes = isset($configuration['processes']) ?
            $configuration['processes'] :
            self::DEFAULT_PARALLEL_PROCESSES
        ;

        $this->allowOverwriting = false !== getenv('NO_OVERWRITE') ?
            false :
            self::DEFAULT_OVERWRITING_PROCESS
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessUpdateFrequency()
    {
        return $this->updateFrequency;
    }

    /**
     * {@inheritdoc}
     */
    public function isOverwritingEnabled()
    {
        return $this->allowOverwriting;
    }
}
