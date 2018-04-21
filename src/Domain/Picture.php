<?php

declare(strict_types=1);

namespace Taghond\Domain;

class Picture
{
    /**
     * @var string
     */
    private $pathToFile;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * Picture constructor.
     *
     * @param string $pathToFile
     */
    public function __construct(string $pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    /**
     * @return string
     */
    public function getPathToFile(): string
    {
        return $this->pathToFile;
    }

    /**
     * @param Tag $tag
     *
     * @return int
     */
    public function addTag(Tag $tag): int
    {
        $this->tags[] = $tag;

        return \count($this->tags);
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
