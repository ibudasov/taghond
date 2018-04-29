<?php

declare(strict_types=1);

namespace Taghond\Tests\Application;

use PHPUnit\Framework\TestCase;
use Taghond\Application\PictureApplicationService;
use Taghond\Domain\Picture;
use Taghond\Domain\Recognizer;
use Taghond\Domain\Tag;
use Taghond\Domain\TagReader;
use Taghond\Domain\TagWriter;

class PictureApplicationServiceTest extends TestCase
{
    public function testThatPictureCanBeUpdatedAccordingToNewTags(): void
    {
        $pictureMock = \Mockery::mock(Picture::class);

        $currentTagMock = \Mockery::mock(Tag::class);
        $currentTagsMock = [$currentTagMock];

        $tagReaderMock = \Mockery::mock(TagReader::class);
        $tagReaderMock->shouldReceive('readTags')
            ->once()
            ->with($pictureMock)
            ->andReturn($currentTagsMock);

        $recognizedTagMock = \Mockery::mock(Tag::class);

        $recognizerMock = \Mockery::mock(Recognizer::class);
        $recognizerMock->shouldReceive('recognize')
            ->once()
            ->with($pictureMock)
            ->andReturn($pictureMock);

        $pictureMock->shouldReceive('addTag')
            ->once()
            ->with($currentTagMock)
            ->andReturn(1);

        $pictureMock->shouldReceive('addTag')
            ->once()
            ->with($recognizedTagMock)
            ->andReturn(2);

        $tagWriterMock = \Mockery::mock(TagWriter::class);
        $tagWriterMock->shouldReceive('writeTags')
            ->once()
            ->with($pictureMock);

        $pictureMock->shouldReceive('getTags')
            ->once()
            ->andReturn([$currentTagMock, $recognizedTagMock]);

        $service = new PictureApplicationService(
            $tagReaderMock,
            $tagWriterMock,
            $recognizerMock
        );

        self::assertSame($pictureMock, $service->updatePicture($pictureMock));
        self::assertEquals(
            [$currentTagMock, $recognizedTagMock],
            ($service->updatePicture($pictureMock))->getTags()
        );
    }
}
