<?php

namespace PrestaShop\Composer\Exceptions;

use Symfony\Component\Process\Process;
use Exception;

/**
 * This exception is thrown if an operation on Process ends up invalid.
 */
final class InvalidOperationException extends Exception
{
    /**
     * @var Process the Operation process
     */
    public function __construct(Process $process)
    {
        $error = sprintf(
            'The command "%s" failed.' . "\n\nExit Code: %s(%s)\n\nWorking directory: %s",
            $process->getCommandLine(),
            $process->getExitCode(),
            $process->getExitCodeText(),
            $process->getWorkingDirectory()
        );

        if (!$process->isOutputDisabled()) {
            $error .= sprintf(
                '\n\nOutput:\n================\n%s\n\nError Output:\n================\n%s',
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        parent::__construct($error);
    }
}
