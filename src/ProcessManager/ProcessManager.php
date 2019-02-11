<?php

namespace PrestaShop\Composer\ProcessManager;

use Symfony\Component\Process\Process;
use Composer\IO\IOInterface;
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
    private $updateFrequency;

    /**
     * @var int
     */
    private $maxParallelProcesses;

    /**
     * @var IOInterface the IO interface
     */
    private $io;

    /**
     * @var Process[]
     */
    private $processes = [];

    /**
     * @var int the operations to be processed
     */
    private $operations = 0;

    public function __construct($updateFrequency, $maxParallelProcesses, IOInterface $io)
    {
        $this->updateFrequency = $updateFrequency;
        $this->maxParallelProcesses = $maxParallelProcesses;
        $this->io = $io;
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
        if (count($this->processes) === 0) {
            $this->io->writeError('No operations to process');
        }

        $this->io->write('<info>Starting operations...</info>');
        $batchOfProcesses = array_chunk($this->processes, $this->maxParallelProcesses);
        $output = '';

        foreach ($batchOfProcesses as $processes) {
            $output .= $this->runProcesses($processes);
        }

        return $output;
    }

    /**
     * @param array $processes a list of processes to execute and check
     */
    private function runProcesses(array $processes)
    {
        $runningProcesses = count($processes);
        $outputResult = '';

        foreach ($processes as $process) {
            $process->start();
        }

        while ($runningProcesses > 0) {
            foreach ($processes as $process) {
                if (!$process->isRunning()) {
                    --$runningProcesses;
                    ++$this->operations;
                }

                usleep($this->updateFrequency);
                $this->io->overwrite(
                    sprintf(
                        '<info>Processing operations... (%s/%s) </info>',
                        $this->operations,
                        count($this->processes)
                    )
                );
            }
        }

        foreach ($processes as $process) {
            $outputResult .= $process->getOutput();
        }

        return $outputResult;
    }
}
