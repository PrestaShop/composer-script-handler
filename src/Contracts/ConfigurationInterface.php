<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Simple value object to store the Composer extension configuration
 */
interface ConfigurationInterface
{
    /**
     * @var int the number of allowed parallel PHP processes
     */
    const DEFAULT_PARALLEL_PROCESSES = 2;

    /**
     * @var int the time to wait before check the status of a process (in ms)
     */
    const DEFAULT_PROCESS_UPDATE_FREQUENCY = 2000;

    /**
     * @var bool default value for overwriting modules operations
     */
    const DEFAULT_OVERWRITING_PROCESS = true;

    /**
     * @return array the list of modules to manage
     */
    public function getModules();

    /**
     * @return int the number of running processes during operations
     */
    public function getProcesses();

    /**
     * @return int the Process update frequency
     */
    public function getProcessUpdateFrequency();

    /**
     * @return bool checks whether or not we overwrite
     *              existing modules
     */
    public function isOverwritingEnabled();
}
