<?php

namespace PrestaShop\Composer;

use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Filesystem;
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

    /**
     * ConfigurationProcessor constructor.
     *
     * @param IOInterface $io the CLI IO interface
     * @param ExecutorInterface $composerExecutor the Composer command executor
     * @param string $rootPath the Shop root path
     */
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

        foreach ($nativeModules as $moduleName => $moduleVersion) {
            $this->io->write(sprintf('<info>Looked into "%s" module (version %s)</info>', $moduleName, $moduleVersion));

            $package = Package::create($moduleName, $moduleVersion, $this->modulesLocation);
            $modulePath = $this->modulesLocation . $package->getName();
            if (file_exists($modulePath)) {
                $filesystem = new Filesystem();
                $filesystem->remove($modulePath);
                $this->io->write(sprintf('Module "%s" is already installed, deleted.', $package->getName()));
            }

            $this->io->write($this->composerExecutor->executeOnPackage(new CreateProject(), $package));
        }
    }
}
