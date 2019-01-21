<?php

namespace PrestaShop\Composer\Exceptions;

use InvalidArgumentException;

/**
 * This exception is thrown when the PrestaShop "modules" configuration
 * object is invalid.
 */
final class InvalidPackageException extends InvalidArgumentException
{
    public function __construct($expression)
    {
        $exceptionMessage = sprint('Expected expression like prestashop/some_module:1.0, got "%s"', $expression);

        return parent::construct($exceptionMessage);
    }
}
