<?php

declare(strict_types=1);

namespace Taghond\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Taghond\Domain\Picture;
use Taghond\Domain\Tag;

class PictureTest extends TestCase
{
    public function testThatPictureCanBeCreatedAndPathToFileCanBeRetrieved(): void
    {
        $expectedPathToFile = '/tmp';
        $picture = new Picture($expectedPathToFile);

        self::assertEquals($expectedPathToFile, $picture->getPathToFile());
    }

    public function testThatTagCanBeAddedToThePicture(): void
    {
        $picture = new Picture('/tmp');

        $tagMock = \Mockery::mock(Tag::class);

        self::assertEquals(1, $picture->addTag($tagMock));
    }

    public function testThatTagsCanBeRetrieved(): void
    {
        $picture = new Picture('/tmp');

        $tag = new Tag('ok');

        $picture->addTag($tag);

        self::assertEquals(['ok' => $tag], $picture->getTags());
    }

    public function testThatOnlyUniqueTagsCanBeAdded(): void
    {
        $picture = new Picture('/tmp');

        $picture->addTag(new Tag('ok'));
        $picture->addTag(new Tag('ok'));
        $picture->addTag(new Tag('no'));

        self::assertEquals(2, \count($picture->getTags()));
    }
}
