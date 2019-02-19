<?php

namespace Tests\PrestaShop\Composer\Exceptions;

use PrestaShop\Composer\Exceptions\InvalidOperationException;
use Symfony\Component\Process\Process;
use PHPUnit\Framework\TestCase;
use Exception;

final class InvalidOperationExceptionTest extends TestCase
{
    public function testCreation()
    {
        $process = new Process('ls -la');
        $process->start();

        $this->assertInstanceOf(
            InvalidOperationException::class,
            new InvalidOperationException($process)
        );
    }

    public function testExceptionMessageIsRightWhenOuputEnabled()
    {
        $process = $this->getProcess(true);
        $exception = new InvalidOperationException($process);

        $this->assertValidExceptionOutput($exception);
        $this->assertContains('Output:', $exception->getMessage());
        $this->assertContains('Error Output:', $exception->getMessage());
    }

    public function testExceptionMessageIsRightWhenOuputDisabled()
    {
        $process = $this->getProcess(false);
        $exception = new InvalidOperationException($process);

        $this->assertValidExceptionOutput($exception);
    }

    /**
     * @param bool $outputAllowed
     *
     * @return Process
     */
    private function getProcess($outputAllowed)
    {
        $process = new Process('dont-exists');

        if (false === $outputAllowed) {
            $process->disableOutput();
        }

        $process->start();

        return $process;
    }

    /**
     * @param Exception $exception the Process Operation exception
     *
     * @return void
     */
    private function assertValidExceptionOutput(Exception $exception)
    {
        $this->assertContains(
            'The command "dont-exists" failed.',
            $exception->getMessage()
        );

        $this->assertContains(
            'Exit Code:',
            $exception->getMessage()
        );

        $this->assertContains(
            'Working directory:',
            $exception->getMessage()
        );
    }
}
