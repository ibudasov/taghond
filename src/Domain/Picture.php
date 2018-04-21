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
}
