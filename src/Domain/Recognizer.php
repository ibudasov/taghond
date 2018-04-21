<?php

declare(strict_types=1);

namespace Taghond\Domain;

interface Recognizer
{
    /**
     * @param Picture $picture
     *
     * @return Tag[]
     */
    public function recognize(Picture $picture): array;
}
