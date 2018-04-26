<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use Taghond\Domain\Picture;
use Taghond\Domain\Tag;
use Taghond\Domain\TagReader;

class IptcTagReader implements TagReader
{
    const TAG_KEY = '2#025';
    const IPTC_SECTION = 'APP13';

    /**
     * @param Picture $picture
     *
     * @return Tag[]
     */
    public function readTags(Picture $picture): array
    {
        \getimagesize($picture->getPathToFile(), $info);

        if (false === \is_array($info)) {
            return [];
        }

        $iptc = \iptcparse($info[self::IPTC_SECTION]);

        $result = [];
        foreach (\array_keys($iptc) as $s) {
            for ($i = 0; $i < count($iptc[$s]); ++$i) {
                if (self::TAG_KEY === $s) {
                    $result[] = new Tag($iptc[$s][$i]);
                }
            }
        }

        return $result;
    }
}
