<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;


use Taghond\Domain\Picture;
use Taghond\Domain\Recognizer;
use Taghond\Domain\Tag;

class GoogleRecognizer implements Recognizer
{

    /**
     * @param Picture $picture
     *
     * @return Tag[]
     */
    public function recognize(Picture $picture): array
    {
        return [new Tag('recognized')];
    }
}
