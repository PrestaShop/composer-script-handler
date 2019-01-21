<?php

namespace PrestaShop\Composer\Actions;

use PrestaShop\Composer\Contracts\ActionInterface;

final class CreateProject implements ActionInterface
{
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
        return 'create-project';
    }
}
