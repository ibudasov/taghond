<?php

declare(strict_types=1);

namespace Taghond\Tests\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Taghond\Domain\FileReader;
use Taghond\Domain\Picture;
use Taghond\Infrastructure\RunCommand;

class RunCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $fileReaderMock = \Mockery::mock(FileReader::class);
        $fileReaderMock->shouldReceive('readDirectory')
            ->once()
            ->with('/tmp')
            ->andReturn([new Picture('/tmp/1.jpg')]);

        $application->add(new RunCommand($fileReaderMock));

        $command = $application->find('taghond:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'directoryWithPictures' => '/tmp',
            'basicTags' => 'landscape, amsterdam, netherlands',
            'geoTag' => '52.356582, 4.871792',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Taghond is running', $output);
    }
}
