<?php

namespace Tests\PrestaShop\Composer\Actions;

use PrestaShop\Composer\Actions\Update;
use PHPUnit\Framework\TestCase;

final class UpdateTest extends TestCase
{
    public function testCreation()
    {
        $this->assertInstanceOf(Update::class, new Update());
        $this->assertInstanceOf(Update::class, new Update(
            ['foo' => 'bar']
        ));
    }

    public function testGetName()
    {
        $action = new Update();

        $this->assertSame('update', $action->getName());
    }

    public function testGetArguments()
    {
        $actionsWithoutArguments = new Update();

        $this->assertSame([], $actionsWithoutArguments->getArguments());

        $action = new Update(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $action->getArguments());
    }
}
