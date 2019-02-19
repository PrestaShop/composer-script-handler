<?php

namespace PrestaShop\Composer;

use Composer\Script\Event;
use InvalidArgumentException;
use PrestaShop\Composer\Operations\Installer;

/**
 * Entry point available through Composer script actions.
 *
 * Should be used for install and update actions.
 */
final class ScriptHandler
{
    /**
     * @param Event $event the Composer event
     *
     * @return void the Script output
     */
    public static function install(Event $event)
    {
        $composer = $event->getComposer();
        $rootPath = (string) realpath($composer->getConfig()->get('vendor-dir') . '/..');
        $extras = $composer->getPackage()->getExtra();

        if (self::validateConfiguration($extras)) {
            $event->getIO()->write('<info>PrestaShop Module installer</info>');
            $commandBuilder = new CommandBuilder($rootPath);
            $moduleInstaller = new ModulesInstaller(
                $event->getIO(),
                $commandBuilder,
                $extras['prestashop'],
                $rootPath
            );
            $moduleInstaller->execute();
        }
    }

    /**
     * @param Event $event the Composer event
     *
     * @return void the Script output
     */
    public static function update(Event $event)
    {
        self::install($event);
    }

    /**
     * @param array $configuration the Composer configuration
     *
     * @throws \InvalidArgumentException
     *
     * @return bool true if valid
     */
    public static function validateConfiguration(array $configuration)
    {
        if (!isset($configuration['prestashop'])) {
            throw new InvalidArgumentException('The extra.prestashop key needs to be defined.');
        }

        $config = $configuration['prestashop'];

        if (!is_array($config)) {
            throw new InvalidArgumentException(
                'The extra.prestashop setting must be an array or a configuration object.'
            );
        }

        return isset($configuration['prestashop']['modules']);
    }
}
