<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Taghond\Domain\Picture;
use Taghond\Domain\Recognizer;
use Taghond\Domain\Tag;

/**
 * @see: https://docs.microsoft.com/en-us/azure/cognitive-services/computer-vision/quickstarts/php
 * @see: https://westus.dev.cognitive.microsoft.com/docs/services/56f91f2d778daf23d8ec6739/operations/56f91f2e778daf14a499e1fa
 * @see: http://docs.guzzlephp.org/en/stable/quickstart.html
 */
class MicrosoftRecognizer implements Recognizer
{
    private const API_ENDPOINT = 'https://westeurope.api.cognitive.microsoft.com/vision/v1.0/analyze';
    private const API_TYPE_OF_RECOGNITION = 'Description,Tags';
    private const API_QUERY_PARAMETERS = [
        'visualFeatures' => self::API_TYPE_OF_RECOGNITION,
        'details' => 'Landmarks',
        'language' => 'en',
    ];

    /**
     * @var \stdClass
     */
    private $decodedResponse;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * @var string
     */
    private $caption;

    /**
     * {@inheritdoc}
     */
    public function recognize(Picture $picture): Picture
    {
        $this->generateThumbnail($picture);

        $response = $this->makeRequestToMicrosoft($picture);

        $this->decodedResponse = \json_decode($response->getBody()->getContents());

        $this->setTags();

        $this->setCaption();

        $this->deleteThumbnail($picture);

        return $picture;
    }

    /**
     * Microsoft requires files to be less than 4mb,
     * so we have to downsize original pictures,
     * which originally can be around 20mb.
     *
     * @param Picture $picture
     */
    private function generateThumbnail(Picture $picture): void
    {
        $thumbnailer = new Thumbnailer();
        $thumbnailer->createThumbnail(
            $picture->getPathToFile(),
            $picture->getPathToThumbnailFile(),
            2000,
            2000
        );
    }

    /**
     * @param Picture $picture
     */
    private function deleteThumbnail(Picture $picture): void
    {
        \unlink($picture->getPathToThumbnailFile());
    }

    /**
     * @param Picture $picture
     *
     * @return ResponseInterface
     */
    private function makeRequestToMicrosoft(Picture $picture): ResponseInterface
    {
        $client = new Client();
        $response = $client->post(
            self::API_ENDPOINT,
            [
                'headers' => [
                    'Content-Type' => 'application/octet-stream',
                    'Ocp-Apim-Subscription-Key' => \getenv('IMAGE_RECOGNITION_SERVICE_KEY'),
                ],
                'query' => self::API_QUERY_PARAMETERS,
                'body' => \fopen($picture->getPathToThumbnailFile(), 'r'),
            ]
        );

        return $response;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    private function setTags(): void
    {
        $tags = [];
        foreach ($this->decodedResponse->tags as $tag) {
            $tags[] = new Tag($tag->name);
        }

        $this->tags = $tags;
    }

    private function setCaption(): void
    {
        $this->caption = \current($this->decodedResponse->description->captions)->text;
    }
}
