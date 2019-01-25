<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Define an implementation to manage
 * multiple calls of Process, for performance
 * mainly.
 */
interface ProcessManagerInterace
{
    /**
     * @param string $command the Process command to execute
     * @param string $location the working directory of command to execute
     */
    public function add($command, $location);

    /**
     * Execute all processes and output the results
     *
     * @return string
     */
    public function run();
}
