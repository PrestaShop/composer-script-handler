<?php

namespace PrestaShop\Composer;

use Composer\IO\IOInterface;
use PrestaShop\Composer\Actions\Update;
use PrestaShop\Composer\Actions\CreateProject;
use PrestaShop\Composer\Contracts\ExecutorInterface;

/**
 * According to the configuration of "extras > prestashop" in Composer json file,
 * will call a Process Executor to execute Composer commands.
 */
final class ConfigurationProcessor
{
    const MODULES_PATH = '/modules/';

    /**
     * @var IOInterface the CLI IO interface
     */
    private $io;

    /**
     * @var ExecutorInterface the Composer command executor
     */
    private $composerExecutor;

    /**
     * @var string the modules folder path of the Shop
     */
    private $modulesLocation;

    public function __construct(IOInterface $io, ExecutorInterface $composerExecutor, $rootPath)
    {
        $this->io = $io;
        $this->composerExecutor = $composerExecutor;
        $this->modulesLocation = $rootPath . self::MODULES_PATH;
    }

    /**
     * Foreach module, will install the module dependencies
     * into /modules/{module}/vendor folder.
     *
     * @param array $configuration the Composer configuration
     */
    public function processInstallation(array $configuration)
    {
        $this->io->write('<info>PrestaShop Module installer</info>');

        if (!array_key_exists('modules', $configuration)) {
            return;
        }

        $nativeModules = $configuration['modules'];

        foreach ($nativeModules as $moduleName => $moduleVersion) {
            $this->io->write(sprintf('<info>Looked into "%s" module (version %s)</info>', $moduleName, $moduleVersion));

            $package = Package::create($moduleName, $moduleVersion, $this->modulesLocation);
            if (file_exists($this->modulesLocation . $package->getName())) {
                $this->io->write(sprintf('Module "%s" is already installed, skipped.', $package->getName()));

                return;
            }

            $this->composerExecutor->executeOnPackage(new CreateProject(), $package);
        }
    }

    /**
     * Foreach module, will install the module dependencies
     * into /modules/{module}/vendor folder.
     *
     * @param array $configuration the Composer configuration
     */
    public function processUpdate(array $configuration)
    {
        $this->io->write('<info>PrestaShop Module installer</info>');
        if (!array_key_exists('modules', $configuration)) {
            return;
        }

        $nativeModules = $configuration['modules'];

        foreach ($nativeModules as $moduleName => $moduleVersion) {
            $this->io->write(sprintf('<info>Looked into "%s" module (version %s)</info>', $moduleName, $moduleVersion));
            $package = Package::create($moduleName, $moduleVersion, $this->modulesLocation);

            $this->composerExecutor->executeOnPackage(new Update(), $package);
        }
    }
}
