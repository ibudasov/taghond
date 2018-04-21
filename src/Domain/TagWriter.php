<?php

declare(strict_types=1);

namespace Taghond\Domain;

interface TagWriter
{
    /**
     * @param Picture $picture
     *
     * @return Picture
     */
    public function writeTags(Picture $picture): Picture;
}
