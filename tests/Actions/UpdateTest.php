<?php

namespace Tests\PrestaShop\Composer\Actions;

use PrestaShop\Composer\Actions\Update;
use PHPUnit\Framework\TestCase;

final class UpdateTest extends TestCase
{
    public function testCreation()
    {
        $this->assertInstanceOf(Update::class, new Update());
        $arguments = ['foo' => 'bar'];

        $this->assertInstanceOf(
            Update::class,
            new Update($arguments)
        );
    }

    public function testGetName()
    {
        $action = new Update();

        $this->assertSame('update', $action->getName());
    }

    public function testGetArgumentsWithoutArguments()
    {
        $actionsWithoutArguments = new Update();

        $this->assertSame([], $actionsWithoutArguments->getArguments());
    }

    public function testGetArguments()
    {
        $arguments = ['foo' => 'bar'];
        $action = new Update($arguments);

        $this->assertSame(
            $arguments,
            $action->getArguments()
        );
    }
}
