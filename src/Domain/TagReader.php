<?php

declare(strict_types=1);

namespace Taghond\Domain;

interface TagReader
{
    /**
     * @param Picture $picture
     * @return Tag[]
     */
    public function readTags(Picture $picture): array;
}
