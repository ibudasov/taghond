<?php

declare(strict_types=1);

namespace Taghond\Tests\Infrastructure;

use PHPUnit\Framework\TestCase;
use Taghond\Domain\Picture;
use Taghond\Domain\Tag;
use Taghond\Infrastructure\MicrosoftRecognizer;

class MicrosoftRecognizerTest extends TestCase
{
    public function testThatPictureCanBeRecognized(): void
    {
        $recognizer = new MicrosoftRecognizer();

        $pictureMock = \Mockery::mock(Picture::class);

        self::assertInternalType('array', $recognizer->recognize($pictureMock));
        self::assertInstanceOf(Tag::class, \current($recognizer->recognize($pictureMock)));
    }
}
