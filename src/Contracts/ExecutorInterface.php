<?php

namespace PrestaShop\Composer\Contracts;

interface ExecutorInterface
{
    /**
     * Will execute an action on a package
     *
     * @param ActionInterface $action the Composer action
     * @param PackageInterface $package the Composer package
     */
    public function execute(ActionInterface $action, PackageInterface $package);
}
