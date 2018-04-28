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
     * @var string
     */
    private $fileName;

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

        $chunksOfPathToFile = \explode('/', $pathToFile);
        $this->fileName = \end($chunksOfPathToFile);
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
        $this->tags[(string) $tag] = $tag;

        return \count($this->tags);
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

}
