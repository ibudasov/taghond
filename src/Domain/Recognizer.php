<?php

declare(strict_types=1);

namespace Taghond\Domain;

interface Recognizer
{
    /**
     * @param Picture $picture
     *
     * @return Picture
     */
    public function recognize(Picture $picture): Picture;

    /**
     * @return Tag[]
     */
    public function getTags(): array;

    /**
     * @return string
     */
    public function getCaption(): string;
}
