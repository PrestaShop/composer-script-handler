<?php

namespace PrestaShop\Composer\ProcessManager;

use Symfony\Component\Process\Process;
use PrestaShop\Composer\Contracts\ProcessManagerInterface;

/**
 * Main implementation of Process Manager, used to
 * parallelize Process calls.
 */
final class ProcessManager implements ProcessManagerInterface
{
    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var int
     */
    private $maxParallelProcesses;

    /**
     * @var Process[]
     */
    private $processes;

    /**
     * @var int
     */
    private $runningProcesses;

    public function __construct($timestamp, $maxParallelProcesses)
    {
        $this->timestamp = $timestamp;
        $this->maxParallelProcesses = $maxParallelProcesses;
    }

    /**
     * {@inheritdoc}
     */
    public function add($command, $location)
    {
        $this->processes[] = (new Process($command))
            ->setWorkingDirectory($location)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $batchOfProcesses = array_chunk($this->processes, $this->maxParallelProcesses);

        foreach ($batchOfProcesses as $processes) {
            $this->runProcesses($processes);
        }
    }

    private function runProcesses(array $processes)
    {
        $runningProcesses = count($processes);

        foreach ($processes as $process) {
            $process->start();
        }

        while ($runningProcesses > 0) {
            foreach ($processes as $process) {
                if (!$process->isRunning()) {
                    --$runningProcesses;
                }

                usleep($this->timestamp);
            }
        }
    }
}
