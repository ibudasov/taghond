<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use iBudasov\Iptc\Domain\Tag;
use iBudasov\Iptc\Manager;
use Taghond\Domain\Picture;
use Taghond\Domain\TagWriter;

class IptcTagWriter implements TagWriter
{
    /**
     * @param Picture $picture
     *
     * @return Picture
     *
     * @throws \Exception
     */
    public function writeTags(Picture $picture): Picture
    {
        $iptcTagManager = Manager::create();

        $iptcTagManager->loadFile($picture->getPathToFile());

        $keywords = [];
        foreach ($picture->getTags() as $tag) {
            $keywords[] = $tag->getValue();
        }

        $iptcTagManager->addTag(new Tag(Tag::KEYWORDS, $keywords));
        $iptcTagManager->addTag(new Tag(Tag::DESCRIPTION, [$picture->getCaption()]));

        $iptcTagManager->write();

        return $picture;
    }
}
