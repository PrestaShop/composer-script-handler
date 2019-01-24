<?php

namespace PrestaShop\Composer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PrestaShop\Composer\Contracts\ExecutorInterface;
use PrestaShop\Composer\Contracts\PackageInterface;
use PrestaShop\Composer\Contracts\ActionInterface;

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
     * @param string $rootPath the root path of the Shop
     */
    public function __construct($rootPath)
    {
        $this->composer = $rootPath . '/vendor/composer/composer/bin/composer';
        $this->rootPath = $rootPath;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ActionInterface $action, $location)
    {
        $command = $this->composer . ' '
            . $action->getName()
        ;

        $actionArgs = $action->getArguments();

        if (count($actionArgs) > 0) {
            $command .= ' ' . implode(' ', $actionArgs);
        }

        return $this->executeProcess($command, $location);
    }

    /**
     * {@inheritdoc}
     */
    public function executeOnPackage(ActionInterface $action, PackageInterface $package)
    {
        $command = $this->composer . ' '
            . $action->getName() . ' '
            . $package->getCompleteName()
        ;

        $actionArgs = $action->getArguments();

        if (count($actionArgs) > 0) {
            $command .= ' ' . implode(' ', $actionArgs);
        }

        return $this->executeProcess($command, $package->getDestination());
    }

    /**
     * @param string $command the Process command
     * @param string $location the Process location
     *
     * @throws ProcessFailedException
     *
     * @return string
     */
    private function executeProcess($command, $location)
    {
        $process = (new Process($command))
            ->setWorkingDirectory($location)
            ->setTimeout(60)
            ->disableOutput()
        ;

        try {
            $process->mustRun();

            return '[OK]';
        } catch (ProcessFailedException $exception) {
            return $exception->getMessage();
        }
    }
}
