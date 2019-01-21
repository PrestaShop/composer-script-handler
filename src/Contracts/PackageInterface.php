<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Define what is a package from the Composer extension
 * point of view.
 */
interface PackageInterface
{
    /**
     * @return array the package name
     */
    public function getName();

    /**
     * @return string the package owner
     */
    public function getOwner();

    /**
     * @return string the package version
     */
    public function getVersion();

    /**
     * @return string the package destination location
     */
    public function getDestination();
}
