<?php

namespace PrestaShop\Composer;

use Symfony\Component\Process\Process;
use PrestaShop\Composer\Contracts\CommandBuilderInterface;
use PrestaShop\Composer\Contracts\PackageInterface;
use PrestaShop\Composer\Contracts\ActionInterface;

/**
 * Using Symfony Process Component, executes Composer actions
 */
final class CommandBuilder implements CommandBuilderInterface
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
    public function getCommand(ActionInterface $action)
    {
        $command = $this->composer . ' '
            . $action->getName()
        ;

        $actionArgs = $action->getArguments();

        if (count($actionArgs) > 0) {
            $command .= ' ' . implode(' ', $actionArgs);
        }

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandOnPackage(ActionInterface $action, PackageInterface $package)
    {
        $command = $this->composer . ' '
            . $action->getName() . ' '
            . $package->getCompleteName()
        ;

        $actionArgs = $action->getArguments();

        if (count($actionArgs) > 0) {
            $command .= ' ' . implode(' ', $actionArgs);
        }

        return $command;
    }
}
