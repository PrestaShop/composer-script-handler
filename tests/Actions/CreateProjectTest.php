<?php

namespace Tests\PrestaShop\Composer\Actions;

use PrestaShop\Composer\Actions\CreateProject;
use PHPUnit\Framework\TestCase;

final class CreateProjectTest extends TestCase
{
    public function testCreation()
    {
        $this->assertInstanceOf(CreateProject::class, new CreateProject());
        $this->assertInstanceOf(CreateProject::class, new CreateProject(
            ['foo' => 'bar']
        ));
    }

    public function testGetName()
    {
        $action = new CreateProject();

        $this->assertSame('create-project', $action->getName());
    }

    public function testGetArguments()
    {
        $actionsWithoutArguments = new CreateProject();

        $this->assertSame([
            '--no-scripts',
            '--no-progress',
            '--no-interaction',
        ], $actionsWithoutArguments->getArguments());

        $action = new CreateProject(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $action->getArguments());
    }
}
