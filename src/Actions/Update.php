<?php

namespace PrestaShop\Composer\Actions;

use PrestaShop\Composer\Contracts\ActionInterface;

/**
 * Composer "update" action of Composer
 */
final class Update implements ActionInterface
{
    /**
     * @var array the action arguments
     */
    private $actionsArguments;

    public function __construct(array $actionsArguments = [])
    {
        $this->actionsArguments = $actionsArguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return $this->actionsArguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'update';
    }
}
