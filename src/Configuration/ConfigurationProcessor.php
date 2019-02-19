<?php

namespace PrestaShop\Composer\Configuration;

use PrestaShop\Composer\Contracts\ConfigurationProcessorInterface;

/**
 * This class responsibility is to retrieve an extension configuration
 * from Composer configuration.
 */
final class ConfigurationProcessor implements ConfigurationProcessorInterface
{
    /**
     * @var array the Composer configuration
     */
    private $composerConfiguration;

    public function __construct(array $composerConfiguration)
    {
        $this->composerConfiguration = $composerConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return new ExtensionConfiguration($this->composerConfiguration);
    }
}
