<?php

declare(strict_types=1);

namespace Taghond\Tests\Infrastructure;


use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Taghond\Domain\Picture;
use Taghond\Infrastructure\IptcTagReader;

function getimagesize($param1, $param2) {
    return IptcTagReaderTest::$functions->getimagesize($param1, $param2);
}

class IptcTagReaderTest extends TestCase
{
    /** @var MockInterface */
    public static $functions;

    public function setUp()
    {
        self::$functions = \Mockery::mock();
    }


    public function testThatTagsCanBeRead(): void
    {
        $reader = new IptcTagReader();

        self::$functions->shouldReceive('getimagesize')->once();

        $pictureMock = \Mockery::mock(Picture::class);
        $pictureMock->shouldReceive('getPathToFile')->once()->andReturn('/tmp');

//        $result = $reader->readTags($pictureMock);

//        self::assertInternalType('array', $result);

    }
}
