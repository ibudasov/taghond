<?php

declare(strict_types=1);

namespace Taghond\Tests\Infrastructure;

use Taghond\Domain\Picture;
use Taghond\Infrastructure\MicrosoftRecognizer;

class MicrosoftRecognizerTest //extends TestCase
{
    public function testThatPictureCanBeRecognized(): void
    {
        $recognizer = new MicrosoftRecognizer();

        $pictureMock = \Mockery::mock(Picture::class);
        $pictureMock->shouldReceive('getPathToFile')->once()->andReturn('/tmp');

        self::assertInstanceOf(Picture::class, \current($recognizer->recognize($pictureMock)));
    }
}
