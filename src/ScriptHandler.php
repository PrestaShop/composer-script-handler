<?php

namespace PrestaShop\Composer;

use Composer\Script\Event;

final class ScriptHandler
{
    public static function install(Event $event)
    {
        $composer = $event->getComposer();
        $rootPath = (string) realpath($composer->getConfig()->get('vendor-dir') . '/..');

        $extras = $composer->getPackage()->getExtra();

        if (!isset($extras['prestashop'])) {
            throw new \InvalidArgumentException('The extra.prestashop key needs to be defined.');
        }

        $config = $extras['prestashop'];

        if (!is_array($config)) {
            throw new \InvalidArgumentException(
                'The extra.prestashop setting must be an array or a configuration object.'
            );
        }

        $processExecutor = new ProcessExecutor($rootPath);
        $processor = new ConfigurationProcessor($event->getIO(), $processExecutor);
        $processor->processInstallation($config, $rootPath);
    }

    public static function update(Event $event)
    {
        $composer = $event->getComposer();
        $rootPath = (string) realpath($composer->getConfig()->get('vendor-dir') . '/..');

        $extras = $composer->getPackage()->getExtra();

        if (!isset($extras['prestashop'])) {
            throw new \InvalidArgumentException(
                'The parameter handler needs to be configured through the extra.prestashop-modules setting.'
            );
        }

        $config = $extras['prestashop'];

        if (!is_array($config)) {
            throw new \InvalidArgumentException(
                'The extra.prestashop setting must be an array or a configuration object.'
            );
        }

        $processExecutor = new ProcessExecutor($rootPath);
        $processor = new ConfigurationProcessor($event->getIO(), $processExecutor);
        $processor->processUpdate($config, $rootPath);
    }
}
