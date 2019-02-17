<?php

namespace PrestaShop\Composer\Installer;

use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Filesystem;
use PrestaShop\Composer\ProcessManager\ProcessManager;
use PrestaShop\Composer\Actions\CreateProject;
use PrestaShop\Composer\Contracts\CommandBuilderInterface;

final class ModulesInstaller
{
    /**
     * @var string the modules path
     */
    const MODULES_PATH = '/modules/';

    /**
     * @var IOInterface the CLI IO interface
     */
    private $io;

    /**
     * @var CommandBuilderInterface the Composer command builder
     */
    private $commandBuilder;

    /**
     * @var string the modules folder path of the Shop
     */
    private $modulesLocation;

    /**
     * @param IOInterface $io the CLI IO interface
     * @param CommandBuilderInterface $commandBuilder the Composer command executor
     * @param string $rootPath the Shop root path
     */
    public function __construct(IOInterface $io, CommandBuilderInterface $commandBuilder, $rootPath)
    {
        $this->io = $io;
        $this->commandBuilder = $commandBuilder;
        $this->modulesLocation = $rootPath . self::MODULES_PATH;
    }

    public function execute()
    {
        $configuration = new ExtensionConfiguration($configuration);

        $processManager = new ProcessManager(
            $configuration->getUpdateFrequency(),
            $configuration->getProcesses(),
            $this->io
        );

        $shouldOverWrite = $this->io->askConfirmation(
            'Do you want to overwrite the existing modules? (y/n)',
            $configuration->isOverwritingEnabled()
        );

        foreach ($configuration->getModules() as $moduleName => $moduleVersion) {
            $package = Package::create($moduleName, $moduleVersion, $this->modulesLocation);
            $modulePath = $this->modulesLocation . $package->getName();

            if ($shouldOverWrite) {
                if (file_exists($modulePath)) {
                    $filesystem = new Filesystem();
                    $filesystem->remove($modulePath);
                    $this->io->write(sprintf('Module "%s" will be overwritten.', $package->getName()));
                }
            }

            if (!file_exists($modulePath)) {
                $this->io->write(
                    sprintf(
                        '<info>Installation of "%s" module (version %s)</info>',
                        $moduleName,
                        $moduleVersion
                    ),
                    true,
                    IOInterface::VERBOSE
                );

                $command = $this->commandBuilder->getCommandOnPackage(new CreateProject(), $package);
                $processManager->add($command, $this->modulesLocation);
            }
        }

        $this->io->write($processManager->run());
    }
}
