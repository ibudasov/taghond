<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use iBudasov\Iptc\Manager;
use Taghond\Domain\Picture;
use Taghond\Domain\Tag;
use Taghond\Domain\TagReader;

class IptcTagReader implements TagReader
{
    /**
     * @param Picture $picture
     *
     * @return Tag[]
     */
    public function readTags(Picture $picture): array
    {
        $iptcTagManager = Manager::create();
        
        $iptcTagManager->loadFile($picture->getPathToFile());
        
        $tags = $iptcTagManager->getTags();

        $result = [];
        
        foreach ($tags as $tag) {
            $result[] = new Tag((string) $tag);
        }

        return $result;
    }
}
