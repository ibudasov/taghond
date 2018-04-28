<?php

declare(strict_types=1);

namespace Taghond\Tests\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Taghond\Application\PictureApplicationService;
use Taghond\Domain\FileReader;
use Taghond\Domain\Picture;
use Taghond\Infrastructure\RunCommand;

class RunCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $pictureMock = \Mockery::mock(Picture::class);
        $pictureMock->shouldReceive('getTags')
            ->once()
            ->andReturn([]);
        $pictureMock->shouldReceive('getFileName')
            ->once()
            ->andReturn('DSCF9146.jpg');

        $fileReaderMock = \Mockery::mock(FileReader::class);
        $fileReaderMock->shouldReceive('readDirectory')
            ->once()
            ->with('/tmp')
            ->andReturn([$pictureMock]);

        $pictureApplicationServiceMock = \Mockery::mock(PictureApplicationService::class);
        $pictureApplicationServiceMock->shouldReceive('updatePicture')
            ->once()
            ->with($pictureMock)
            ->andReturn($pictureMock);

        $application->add(new RunCommand($fileReaderMock, $pictureApplicationServiceMock));

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
