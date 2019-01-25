<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Define a contract to execute Composer commands.
 * Basically, a Composer command is composed of an action ("create-project")
 * and can include package information ("owner/package-name:version").
 */
interface CommandBuilderInterface
{
    /**
     * Will retrieve a command from an action, like "composer show"
     *
     * @param ActionInterface $action the Composer action
     *
     * @return string the execution output
     */
    public function getCommand(ActionInterface $action);

    /**
     * Will retrieve a command from an action on a package,
     * like "composer install owner/package-name:version".
     *
     * @param ActionInterface $action the Composer action
     * @param PackageInterface $package the Composer package
     *
     * @return string the execution output
     */
    public function getCommandOnPackage(ActionInterface $action, PackageInterface $package);
}
