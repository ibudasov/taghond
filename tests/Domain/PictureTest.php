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
        $expectedPathToFile = '/Users/igorbudasov/Desktop/forShutterstock/DSCF9146.jpg';
        $picture = new Picture($expectedPathToFile);

        self::assertEquals($expectedPathToFile, $picture->getPathToFile());
    }

    public function testThatTagCanBeAddedToThePicture(): void
    {
        $picture = new Picture('/Users/igorbudasov/Desktop/forShutterstock/DSCF9146.jpg');

        $tagMock = \Mockery::mock(Tag::class);

        self::assertEquals(1, $picture->addTag($tagMock));
    }

    public function testThatTagsCanBeRetrieved(): void
    {
        $picture = new Picture('/Users/igorbudasov/Desktop/forShutterstock/DSCF9146.jpg');

        $tag = new Tag('ok');

        $picture->addTag($tag);

        self::assertEquals(['ok' => $tag], $picture->getTags());
    }

    public function testThatOnlyUniqueTagsCanBeAdded(): void
    {
        $picture = new Picture('/Users/igorbudasov/Desktop/forShutterstock/DSCF9146.jpg');

        $picture->addTag(new Tag('ok'));
        $picture->addTag(new Tag('ok'));
        $picture->addTag(new Tag('no'));

        self::assertEquals(2, \count($picture->getTags()));
    }

    public function testThatFileNameIsDeductedAndCanBeRetrieved(): void
    {
        $picture = new Picture('/Users/igorbudasov/Desktop/forShutterstock/DSCF9146.jpg');

        self::assertEquals('DSCF9146.jpg', $picture->getFileName());
    }

    public function testThatPictureCanDeductThePathToFileOfThumbnail(): void
    {
        $picture = new Picture('/Users/igorbudasov/Desktop/forShutterstock/DSCF9146.jpg');

        self::assertEquals(
            '/Users/igorbudasov/Desktop/forShutterstock/DSCF9146_thumbnail.jpg',
            $picture->getPathToThumbnailFile()
        );
    }
}
