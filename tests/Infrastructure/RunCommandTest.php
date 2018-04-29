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
        $pictureMock->shouldReceive('getCaption')
            ->once()
            ->andReturn('Some caption');

        $fileReaderMock = \Mockery::mock(FileReader::class);
        $fileReaderMock->shouldReceive('readDirectory')
            ->once()
            ->with(RunCommand::TAGHOND_WORKING_DIRECTORY)
            ->andReturn([$pictureMock]);

        $expectedCaptionPrefix = 'Norway, Trondheim: ';
        $pictureApplicationServiceMock = \Mockery::mock(PictureApplicationService::class);
        $pictureApplicationServiceMock->shouldReceive('updatePicture')
            ->once()
            ->with($pictureMock, $expectedCaptionPrefix)
            ->andReturn($pictureMock);

        $application->add(new RunCommand($fileReaderMock, $pictureApplicationServiceMock));

        $command = $application->find('taghond:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'captionPrefix' => $expectedCaptionPrefix,
            'basicTags' => 'landscape, amsterdam, netherlands',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Taghond is running', $output);
    }
}
