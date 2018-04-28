<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use GuzzleHttp\Client;
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
    private const API_KEY = '115903d1abde4cbcb442d5a6714e5937';
    private const API_ENDPOINT = 'https://westcentralus.api.cognitive.microsoft.com/vision/v1.0/analyze';
    private const API_TYPE_OF_RECOGNITION = 'Tags,Description';

    /**
     * @param Picture $picture
     *
     * @return Tag[]
     */
    public function recognize(Picture $picture): array
    {
        $client = new Client();

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Ocp-Apim-Subscription-Key' => self::API_KEY,
        ];

        $parameters = [
            'visualFeatures' => self::API_TYPE_OF_RECOGNITION,
            'details' => 'Landmarks',
            'language' => 'en',
        ];

        $response = $client->request(
            'POST',
            self::API_ENDPOINT,
            [
                'headers' => $headers,
                'query' => $parameters,
                'body' => fopen($picture->getPathToFile(), 'r'),
            ]
        );

        $decodedResponse = \json_decode($response->getBody()->getContents());

        $tags = [];
        foreach ($decodedResponse->description->tags as $tag) {
            $tags[] = new Tag($tag);
        }

        return $tags;
    }
}
