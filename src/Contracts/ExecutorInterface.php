<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Define a contract to execute Composer commands.
 * Basically, a Composer command is composed of an action ("create-project")
 * and can include package information ("owner/package-name:version").
 */
interface ExecutorInterface
{
    /**
     * Will execute an action, like "composer show"
     *
     * @param ActionInterface $action the Composer action
     * @param string $location the Composer json file location directory
     */
    public function execute(ActionInterface $action, $location);

    /**
     * Will execute an action on a package, like "composer install owner/package-name:version".
     *
     * @param ActionInterface $action the Composer action
     * @param PackageInterface $package the Composer package
     */
    public function executeOnPackage(ActionInterface $action, PackageInterface $package);
}
