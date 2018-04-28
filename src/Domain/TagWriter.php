<?php

declare(strict_types=1);

namespace Taghond\Domain;

interface TagWriter
{
    /**
     * Tags which are inside $picture->tags will be written to the file
     *
     * @param Picture $picture
     *
     * @return Picture
     */
    public function writeTags(Picture $picture): Picture;
}
