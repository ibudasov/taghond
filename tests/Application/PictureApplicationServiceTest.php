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
        $picture = new Picture('/Users/igorbudasov/Projects/taghond/var/DSCF9321-HDR.jpg');

        $currentTag = new Tag('current');
        $currentTags = [$currentTag];

        $tagReaderMock = \Mockery::mock(TagReader::class);
        $tagReaderMock->shouldReceive('readTags')
            ->once()
            ->with($picture)
            ->andReturn($currentTags);

        $recognizedTag = new Tag('recognized');
        $recognizedTags = [$recognizedTag];

        $recognizerMock = \Mockery::mock(Recognizer::class);
        $recognizerMock->shouldReceive('recognize')
            ->once()
            ->with($picture)
            ->andReturn($picture);
        $recognizerMock->shouldReceive('getTags')
            ->once()
            ->andReturn($recognizedTags);
        $recognizerMock->shouldReceive('getCaption')
            ->once()
            ->andReturn('caption');

        $tagWriterMock = \Mockery::mock(TagWriter::class);
        $tagWriterMock->shouldReceive('writeTags')
            ->once()
            ->with($picture);

        $service = new PictureApplicationService(
            $tagReaderMock,
            $tagWriterMock,
            $recognizerMock
        );

        $expectedCaptionPrefix = 'CaptionPrefix: ';

        $result = $service->updatePicture($picture, $expectedCaptionPrefix);

        self::assertSame($picture, $result);
        self::assertEquals([(string)$currentTag => $currentTag, (string)$recognizedTag => $recognizedTag], $result->getTags());
        self::assertStringStartsWith($expectedCaptionPrefix, $result->getCaption());
    }
}
