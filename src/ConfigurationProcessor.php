<?php

namespace PrestaShop\Composer;

use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Filesystem;
use PrestaShop\Composer\ProcessManager\ProcessManager;
use PrestaShop\Composer\Actions\CreateProject;
use PrestaShop\Composer\Contracts\CommandBuilderInterface;

/**
 * According to the configuration of "extras > prestashop" in Composer json file,
 * will call a Process Executor to execute Composer commands.
 */
final class ConfigurationProcessor
{
    /**
     * @var string the modules path
     */
    const MODULES_PATH = '/modules/';

    /**
     * @var int the number of allowed parallel PHP processes
     */
    const DEFAULT_PARALLEL_PROCESSES = 2;
    /**
     * @var int the time to wait before check the status of a process (in ms)
     */
    const DEFAULT_PROCESS_UPDATE_FREQUENCY = 200;

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
     * ConfigurationProcessor constructor.
     *
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

    /**
     * Foreach module, will install the module dependencies
     * into /modules/{module}/vendor folder.
     *
     * @param array $configuration the Composer configuration
     *
     * @return void
     */
    public function processInstallation(array $configuration)
    {
        $this->io->write('<info>PrestaShop Module installer</info>');

        if (!isset($configuration['modules'])) {
            return;
        }

        $modules = $configuration['modules'];
        $updateFrequency = isset($configuration['update-frequency']) ?
            $configuration['update-frequency'] :
            self::DEFAULT_PROCESS_UPDATE_FREQUENCY
        ;

        $processes = isset($configuration['processes']) ?
            $configuration['processes'] :
            self::DEFAULT_PARALLEL_PROCESSES
        ;

        $processManager = new ProcessManager($updateFrequency, $processes, $this->io);
        $shouldOverWrite = $this->io->askConfirmation('Do you want to overwrite the existing modules? (y/n)');

        foreach ($modules as $moduleName => $moduleVersion) {
            $package = Package::create($moduleName, $moduleVersion, $this->modulesLocation);
            $modulePath = $this->modulesLocation . $package->getName();

            if ($shouldOverWrite && file_exists($modulePath)) {
                $filesystem = new Filesystem();
                $filesystem->remove($modulePath);
                $this->io->write(sprintf('Module "%s" will be overwritten.', $package->getName()));
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
