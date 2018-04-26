<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use Taghond\Domain\Picture;
use Taghond\Domain\TagWriter;

class IptcTagWriter implements TagWriter
{
    /** @var Iptc */
    private $tagManager;

    /**
     * @param Iptc $tagManager
     */
    public function __construct(Iptc $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * @param Picture $picture
     *
     * @return Picture
     * @throws \Exception
     */
    public function writeTags(Picture $picture): Picture
    {
        $this->tagManager->setFilePath($picture->getPathToFile());

        $tagsToWrite = [];
        foreach ($picture->getTags() as $tag) {
            $tagsToWrite[] = $tag->getValue();
        }

        $this->tagManager->setTagWithValues(Iptc::KEYWORDS, $tagsToWrite);

        $this->tagManager->write();

        return $picture;
    }
}
