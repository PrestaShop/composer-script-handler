<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Creates a Configuration instance from Composer configuration.
 */
interface ConfigurationProcessorInterface
{
    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration();
}
