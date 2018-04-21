<?php

declare(strict_types=1);

namespace Taghond\Domain;

interface FileReader
{
    /**
     * @param string $pathToDirectory
     *
     * @return Picture[]
     */
    public function readDirectory(string $pathToDirectory): array;
}
