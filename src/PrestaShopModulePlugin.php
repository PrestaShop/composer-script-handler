<?php

namespace PrestaShop\ComposerPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Install PrestaShop modules in "modules" folder and
 * executes "composer install" in every module that have a composer.json file.
 */
final class PrestaShopModulePlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new ModuleInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
