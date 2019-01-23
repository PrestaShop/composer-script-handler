<?php

namespace PrestaShop\Composer\Contracts;

/**
 * Allows to run a Composer action
 * like composer "install" or composer "update".
 */
interface ActionInterface
{
    /**
     * @return array the action arguments
     */
    public function getArguments();

    /**
     * @return string the action name
     */
    public function getName();
}
