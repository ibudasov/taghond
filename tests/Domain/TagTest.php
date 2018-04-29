<?php

declare(strict_types=1);

namespace Taghond\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Taghond\Domain\Tag;

class TagTest extends TestCase
{
    public function testThatTagCanBeCreated(): void
    {
        $tagValue = 'norway';

        $tag = new Tag($tagValue);

        self::assertEquals($tagValue, $tag->getValue());
    }

    public function testThatTagCanBeConvertedToString(): void
    {
        $tagValue = 'oslo';

        $tag = new Tag($tagValue);

        self::assertEquals($tagValue, (string) $tag);
    }

    public function testThatTagIsPurifiedWhileCreated(): void
    {
        $tagValue = ' oslo  ';

        $tag = new Tag($tagValue);

        self::assertEquals('oslo', (string) $tag);
    }
}
