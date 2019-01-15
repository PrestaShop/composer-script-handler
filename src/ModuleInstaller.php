<?php

namespace PrestaShop\ComposerPlugin;

use Composer\Installers\Installer;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

final class ModuleInstaller extends Installer
{
    /**
     * @var string the root vendor directory
     */
    private $rootVendorDir;

    /**
     * {@inheritdoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->rootVendorDir = $this->vendorDir;

        // doesn't change the behavior, sadly
        $this->vendorDir = $this->getInstallPath($package) . 'vendor';

        parent::install($repo, $package);

        $this->initializeVendorDir();

        $this->installModuleDependencies();
    }

    /**
     * Extract into another class: mimic calls to Composer.
     */
    public function installModuleDependencies()
    {
        $composerFile = $this->vendorDir . '/../composer.json';

        if (file_exists($composerFile)) {
            $process = new Process(
                'composer install'
            );
            $process->setWorkingDirectory($this->vendorDir . '/../');
            $process->setOptions(['suppress_errors' => false]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo $process->getOutput();

            $process = new Process(
                'composer dump-autoload'
            );
            $process->setOptions(['suppress_errors' => false]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo $process->getOutput();
        }
    }
}
