<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Define what is a package from the Composer extension
 * point of view.
 */
interface PackageInterface
{
    /**
     * @return string the package name
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

    /**
     * @return string the package complete name
     */
    public function getCompleteName();

    /**
     * @return bool checks if the package has been installed
     */
    public function isInstalled();
}
