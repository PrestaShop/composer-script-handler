<?php

namespace PrestaShop\Composer\Contracts;

interface ExecutorInterface
{
    /**
     * Will execute an action
     */
    public function execute(ActionInterface $action);
}
