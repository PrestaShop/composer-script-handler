<?php

namespace PrestaShop\Composer;

use PrestaShop\Composer\Contracts\PackageInterface;
use PrestaShop\Composer\Exceptions\InvalidPackageException;

/**
 * Main implementation of Package.
 */
final class Package implements PackageInterface
{
    /**
     * @var string the package owner
     */
    private $owner;

    /**
     * @var string the package name
     */
    private $name;

    /**
     * @var string the package version
     */
    private $version;

    /**
     * @var string the package destination
     */
    private $destination;

    /**
     * @param string $owner the package owner
     * @param string $name the package name
     * @param string $version the package version
     * @param string $destination the package destination
     */
    public function __construct($owner, $name, $version, $destination)
    {
        $this->owner = $owner;
        $this->name = $name;
        $this->version = $version;
        $this->destination = $destination;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompleteName()
    {
        return $this->getOwner() . '/' . $this->getName() . ':' . $this->getVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * {@inheritdoc}
     */
    public function isInstalled()
    {
        return file_exists($this->destination . $this->name);
    }

    /**
     * Helper function.
     *
     * @param string $expression the dependency expression ("foo/bar")
     * @param string $version the dependency version
     * @param string $destination the location where dependency is installed
     *
     * @return self
     */
    public static function create($expression, $version, $destination)
    {
        list($owner, $name) = explode('/', $expression);

        if (empty($owner) || empty($name)) {
            throw new InvalidPackageException($expression);
        }

        return new self($owner, $name, $version, $destination);
    }
}
