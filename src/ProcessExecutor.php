<?php

namespace PrestaShop\Composer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Using Symfony Process Component, executes Composer actions
 */
final class ProcessExecutor implements ExecutorInterface
{
    /**
     * @var string the Composer executable
     */
    private $composer;

    /**
     * @var string the root path of the Shop
     */
    private $rootPath;

    /**
     * @param string the root path of the Shop
     */
    public function __construct($rootPath)
    {
        $this->composer = $rootPath . '/vendor/composer/composer/bin/composer';
        $this->rootPath = $rootPath;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ActionInterface $action, PackageInterface $package)
    {
        $command = $this->composer . ' '
            . $action->getName() . ' '
            . $package->getName() . ':' . $package->getVersion()
        ;

        $process = new Process(
            $command,
            $package->getDestination()
        );

        try {
            $process->mustRun();
            $this->io->write($process->getOutput());
        } catch (ProcessFailedException $exception) {
            $this->io->writeError($process->getErrorOutput());
        }
    }
}
