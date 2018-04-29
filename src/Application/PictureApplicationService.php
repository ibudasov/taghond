<?php

declare(strict_types=1);

namespace Taghond\Application;

use Taghond\Domain\Picture;
use Taghond\Domain\Recognizer;
use Taghond\Domain\Tag;
use Taghond\Domain\TagReader;
use Taghond\Domain\TagWriter;

class PictureApplicationService
{
    /** @var TagReader */
    private $tagReader;
    /** @var TagWriter */
    private $tagWriter;
    /** @var Recognizer */
    private $recognizer;

    /**
     * @param TagReader  $tagReader
     * @param TagWriter  $tagWriter
     * @param Recognizer $recognizer
     */
    public function __construct(TagReader $tagReader, TagWriter $tagWriter, Recognizer $recognizer)
    {
        $this->tagReader = $tagReader;
        $this->tagWriter = $tagWriter;
        $this->recognizer = $recognizer;
    }

    /**
     * @param Picture $picture
     *
     * @param string $captionPrefix
     * @param Tag[] $basicTags
     * @return Picture
     */
    public function updatePicture(Picture $picture, string $captionPrefix, array $basicTags): Picture
    {
        foreach ($basicTags as $basicTag) {
            $picture->addTag($basicTag);
        }

        $currentTags = $this->tagReader->readTags($picture);

        foreach ($currentTags as $currentTag) {
            $picture->addTag($currentTag);
        }

        $this->recognizer->recognize($picture);

        foreach ($this->recognizer->getTags() as $recognizedRag) {
            $picture->addTag($recognizedRag);
        }

        $picture->setCaption($captionPrefix . $this->recognizer->getCaption());

        $this->tagWriter->writeTags($picture);

        return $picture;
    }
}
