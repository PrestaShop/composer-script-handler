<?php

namespace PrestaShop\Composer;

use Composer\IO\IOInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PrestaShop\Composer\Contracts\ExecutorInterface;

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

    public function __construct(IOInterface $io, ExecutorInterface $composerExecutor)
    {
        $this->io = $io;
        $this->composerExecutor = $composerExecutor;
    }

    /**
     * Foreach module, will install the module dependencies
     * into /modules/{module}/vendor folder.
     *
     * @param array $configuration the Composer configuration
     * @param string $rootPath the root directory of the Shop
     */
    public function processInstallation(array $configuration, $rootPath)
    {
        $composerExecutable = $rootPath . '/vendor/composer/composer/bin/composer';
        $this->io->write('<info>PrestaShop Module installer</info>');

        if (!array_key_exists('native-modules', $configuration) || !array_key_exists('modules-dir', $configuration)) {
            return;
        }

        $nativeModules = $configuration['native-modules'];
        $modulesLocation = $rootPath . self::MODULES_PATH;

        foreach ($nativeModules as $moduleName => $moduleVersion) {
            $this->io->write(sprintf('<info>Looked into "%s" module (version %s)</info>', $moduleName, $moduleVersion));
            $this->installModule($moduleName, $moduleVersion, $modulesLocation, $composerExecutable);
        }
    }

    /**
     * Foreach module, will install the module dependencies
     * into /modules/{module}/vendor folder.
     *
     * @param array $configuration the Composer configuration
     * @param string $rootPath the modules directory location
     */
    public function processUpdate(array $configuration, $rootPath)
    {
        $this->io->write('<info>PrestaShop Module installer</info>');
        if (!array_key_exists('native-modules', $configuration) || !array_key_exists('modules-dir', $configuration)) {
            return;
        }

        $nativeModules = $configuration['native-modules'];
        $modulesLocation = $rootPath . self::MODULES_PATH;

        foreach ($nativeModules as $moduleName => $moduleVersion) {
            $this->io->write(sprintf('<info>Looked into "%s" module (version %s)</info>', $moduleName, $moduleVersion));
            $this->updateModule($moduleName, $moduleVersion, $modulesLocation);
        }
    }

    private function installModule($moduleName, $moduleVersion, $location, $composerExecutable)
    {
        $moduleInformation = Package::createFromString($moduleName, $moduleVersion);
        if (file_exists($location . $moduleInformation->getName())) {
            $this->io->write(sprintf('Module "%s" is already installed, skipped.', $moduleInformation->getName()));

            return;
        }

        $command = "$composerExecutable create-project " . $moduleName . ':' . $moduleVersion;
        $process = new Process($command);
        $process->setWorkingDirectory($location);

        try {
            $process->mustRun();
            $this->io->write(sprintf('Module "%s" successfully installed!', $moduleInformation->getName()));
            $this->io->write($process->getOutput());
        } catch (ProcessFailedException $exception) {
            $this->io->writeError($process->getErrorOutput());
        }
    }

    private function updateModule($moduleName, $moduleVersion, $location)
    {
        $command = 'composer create-project ' . $moduleName . ':' . $moduleVersion;
        $process = new Process($command);
        $process->setWorkingDirectory($location);

        try {
            $process->mustRun();
            $this->io->write($process->getOutput());
        } catch (ProcessFailedException $exception) {
            $this->io->writeError($process->getErrorOutput());
        }
    }
}
