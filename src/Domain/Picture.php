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
    private $pathToThumbnailFile;

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

        $this->fileName = $this->deductFileName();

        $this->pathToThumbnailFile = $this->deductThumbnailPath();

        $this->tags = [];
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

    /**
     * @return string
     */
    public function getPathToThumbnailFile(): string
    {
        return $this->pathToThumbnailFile;
    }

    /**
     * @return string
     */
    private function deductThumbnailPath(): string
    {
        $pathInfo = \pathinfo($this->pathToFile);

        $extension = $pathInfo['extension'] ?? '';

        return \str_replace(
            '.'.$extension,
            '_thumbnail.'.$extension,
            $this->pathToFile
        );
    }

    /**
     * @return string
     */
    private function deductFileName(): string
    {
        $pathInfo = \pathinfo($this->pathToFile);

        return $pathInfo['basename'];
    }
}
