<?php

namespace PrestaShop\Composer\Actions;

use PrestaShop\Composer\Contracts\ActionInterface;

/**
 * Composer "create-project" action of Composer
 */
final class CreateProject implements ActionInterface
{
    /**
     * @var array the action arguments
     */
    private $actionsArguments;

    public function __construct(array $actionsArguments = [])
    {
        if (empty($actionsArguments)) {
            $actionsArguments = [
                '--no-scripts',
                '--no-progress',
                '--no-interaction'
            ];
        }
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
        return 'create-project';
    }
}
