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
    const PARALLEL_PROCESSES = 8;

    /**
     * @var int the timeout before wait the status of a process (in ms)
     */
    const WAIT_FOR_PROCESS_STATUS_TIMEOUT = 100;

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

        if (!array_key_exists('modules', $configuration)) {
            return;
        }

        $nativeModules = $configuration['modules'];
        $timeout = array_key_exists('timeout', $configuration) ?
            $configuration['timeout'] :
            self::WAIT_FOR_PROCESS_STATUS_TIMEOUT
        ;

        $processes = array_key_exists('processes', $configuration) ?
            $configuration['processes'] :
            self::PARALLEL_PROCESSES
        ;

        $processManager = new ProcessManager($timeout, $processes);
        $shouldOverWrite = $this->io->askConfirmation('Do you want to overwrite the existing modules? (y/n)');

        foreach ($nativeModules as $moduleName => $moduleVersion) {
            $package = Package::create($moduleName, $moduleVersion, $this->modulesLocation);
            $modulePath = $this->modulesLocation . $package->getName();

            if ($shouldOverWrite && file_exists($modulePath)) {
                $filesystem = new Filesystem();
                $filesystem->remove($modulePath);
                $this->io->write(sprintf('Module "%s" will be overwritten.', $package->getName()));
            }

            if (!file_exists($modulePath)) {
                $this->io->write(sprintf('<info>Looked into "%s" module (version %s)</info>', $moduleName, $moduleVersion));

                $command = $this->commandBuilder->getCommandOnPackage(new CreateProject(), $package);
                $processManager->add($command, $this->modulesLocation);
            }
        }

        $this->io->write($processManager->run());
    }
}
