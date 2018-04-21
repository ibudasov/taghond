<?php

declare(strict_types=1);

namespace Taghond\Tests\Infrastructure;

use PHPUnit\Framework\TestCase;
use Taghond\Infrastructure\SymfonyFileReader;

class SymfonyFileReaderTest extends TestCase
{
    public function testThatFileReaderCanReadDirectoryWhenItExists(): void
    {
        $fileReader = new SymfonyFileReader();

        self::assertInternalType('array', $fileReader->readDirectory('/tmp'));
//        self::assertInstanceOf(Picture::class, current($fileReader->readDirectory('/tmp')));
    }
}
