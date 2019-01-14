<?php

namespace PrestaShop\ComposerPlugin;

use Composer\Installers\Installer;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

final class ModuleInstaller extends Installer
{
    /**
     * {@inheritdoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        // doesn't change the behavior, sadly
        $this->vendorDir = $this->getInstallPath($package). 'vendor';

        parent::install($repo, $package);
    }
}
