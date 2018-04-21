<?php

declare(strict_types=1);

namespace Taghond\Tests\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Taghond\Infrastructure\RunCommand;

class RunCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new RunCommand());

        $command = $application->find('taghond:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Taghond is running', $output);
    }
}