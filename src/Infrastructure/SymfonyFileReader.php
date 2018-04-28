<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use Symfony\Component\Finder\Finder;
use Taghond\Domain\FileReader;
use Taghond\Domain\Picture;

class SymfonyFileReader implements FileReader
{
    /**
     * @var Finder
     */
    private $finder;

    public function __construct()
    {
        $this->finder = new Finder();
    }

    /**
     * {@inheritdoc}
     */
    public function readDirectory(string $pathToDirectory): array
    {
        $foundFiles = $this->finder
            ->files()
            ->in($pathToDirectory)
            ->name('*.jpeg')
            ->name('*.jpg')
            // this pattern we need to do not process thumbnails
            ->name('/^[a-zA-Z0-9]{1,11}$/')
        ;

        $result = [];
        /** @var \SplFileInfo $file */
        foreach ($foundFiles as $file) {
            $result[] = new Picture($file->getRealPath());
        }

        return $result;
    }
}
