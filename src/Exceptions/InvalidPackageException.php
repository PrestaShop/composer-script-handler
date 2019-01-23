<?php

namespace PrestaShop\Composer\Exceptions;

use InvalidArgumentException;

/**
 * This exception is thrown when the PrestaShop "modules" configuration
 * object is invalid.
 */
final class InvalidPackageException extends InvalidArgumentException
{
    /**
     * @param string $expression the Package expression
     */
    public function __construct($expression)
    {
        $exceptionMessage = sprintf('Expected expression like prestashop/some_module:1.0, got "%s"', $expression);

        parent::__construct($exceptionMessage);
    }
}
