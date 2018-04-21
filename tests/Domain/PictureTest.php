<?php

declare(strict_types=1);

namespace Taghond\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Taghond\Domain\Picture;

class PictureTest extends TestCase
{
    public function testThatPictureCanBeCreatedAndPathToFileCanBeRetrieved(): void
    {
        $expectedPathToFile = '/tmp';
        $picture = new Picture($expectedPathToFile);

        self::assertEquals($expectedPathToFile, $picture->getPathToFile());
    }
}
