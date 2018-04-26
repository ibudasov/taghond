<?php

namespace Taghond\Infrastructure;

class Iptc
{
    const OBJECT_NAME = '005';
    const EDIT_STATUS = '007';
    const PRIORITY = '010';
    const CATEGORY = '015';
    const SUPPLEMENTAL_CATEGORY = '020';
    const FIXTURE_IDENTIFIER = '022';
    const KEYWORDS = '025';
    const RELEASE_DATE = '030';
    const RELEASE_TIME = '035';
    const SPECIAL_INSTRUCTIONS = '040';
    const REFERENCE_SERVICE = '045';
    const REFERENCE_DATE = '047';
    const REFERENCE_NUMBER = '050';
    const CREATED_DATE = '055';
    const CREATED_TIME = '060';
    const ORIGINATING_PROGRAM = '065';
    const PROGRAM_VERSION = '070';
    const OBJECT_CYCLE = '075';
    const CREATOR = '080';
    const CITY = '090';
    const PROVINCE_STATE = '095';
    const COUNTRY_CODE = '100';
    const COUNTRY = '101';
    const ORIGINAL_TRANSMISSION_REFERENCE = '103';
    const HEADLINE = '105';
    const CREDIT = '110';
    const SOURCE = '115';
    const COPYRIGHT_STRING = '116';
    const CAPTION = '120';
    const LOCAL_CAPTION = '121';
    const CAPTION_WRITER = '122';

    /**
     * Stores the IPTC tags.
     *
     * @var array
     */
    private $parsedIptcTags = [];

    /**
     * Checks whether any tag class set.
     *
     * @var bool
     */
    private $hasIptcTags = false;

    /**
     * Valid extensions.
     *
     * @var array
     */
    private $supportedExtensions = ['jpg', 'jpeg', 'pjpeg'];

    /**
     * Image name ex. /home/user/image.jpg.
     *
     * @var string
     */
    private $pathToFile;

    public function setFilePath(string $pathToFile)
    {
        if (!\file_exists($pathToFile)) {
            throw new \InvalidArgumentException('Image not found!');
        }

        if (!\is_writable($pathToFile)) {
            throw new \InvalidArgumentException('File is not writable!');
        }

        $parts = \explode('.', strtolower($pathToFile));

        if (!\in_array(\end($parts), $this->supportedExtensions)) {
            throw new \Exception(
                'Support only for the following extensions: ' . \json_encode($this->supportedExtensions)
            );
        }

        \getimagesize($pathToFile, $imageInfo);
        $this->hasIptcTags = isset($imageInfo['APP13']);
        if ($this->hasIptcTags) {
            $this->parsedIptcTags = \iptcparse($imageInfo['APP13']);
        }

        $this->pathToFile = $pathToFile;
    }


    /**
     * Set parameters you want to record in a particular tag "IPTC".
     *
     * @param string $tag  Code or const of tag, use class' constants
     * @param array  $data Value of tag
     *
     * @return Iptc object
     */
    public function setTagWithValues(string $tag, array $data): self
    {
        $data = $this->decode($data);

        $this->parsedIptcTags[$this->buildTagName($tag)] = $data;
        $this->hasIptcTags = true;

        return $this;
    }

    /**
     * Adds an item at the beginning of the array.
     *
     * @param string $tag
     * @param array  $data
     *
     * @return Iptc
     */
    public function prepend(string $tag, array $data): self
    {
        $data = $this->decode($data);
        $tagName = $this->buildTagName($tag);

        if (!empty($this->parsedIptcTags[$tagName])) {
            \array_unshift($this->parsedIptcTags[$tagName], $data);
            $data = $this->parsedIptcTags[$tagName];
        }

        $this->parsedIptcTags[$tagName] = [$data];
        $this->hasIptcTags = true;

        return $this;
    }

    /**
     * Adds an item at the end of the array.
     *
     * @param string $tag
     * @param array  $data
     *
     * @return Iptc
     */
    public function append(string $tag, array $data): self
    {
        $data = $this->decode($data);
        $tagName = $this->buildTagName($tag);

        if (!empty($this->parsedIptcTags[$tagName])) {
            \array_push($this->parsedIptcTags[$tagName], $data);
            $data = $this->parsedIptcTags[$tagName];
        }

        $this->parsedIptcTags[$tagName] = array($data);
        $this->hasIptcTags = true;

        return $this;
    }

    /**
     * Return first IPTC tag by tag name.
     *
     * @param string $tag
     *
     * @return null|string
     */
    public function fetchFirstValueOfGivenTag(string $tag): ?string
    {
        if (!isset($this->parsedIptcTags[$this->buildTagName($tag)])) {
            return null;
        }

        $neededTagValues = $this->parsedIptcTags[$this->buildTagName($tag)];

        $encodedTagValues = $this->encode($neededTagValues);

        return \current($encodedTagValues);
    }

    /**
     * Return all IPTC tags by tag name.
     *
     * @param string $tag
     *
     * @return array
     */
    public function fetchAllValuesOfGivenTag(string $tag): array
    {
        if (!isset($this->parsedIptcTags[$this->buildTagName($tag)])) {
            return [];
        }

        return $this->encode($this->parsedIptcTags[$this->buildTagName($tag)]);
    }

    /**
     * returns a binary coded string.
     *
     * @return string
     */
    public function binary(): string
    {
        $iptc = '';
        foreach (\array_keys($this->parsedIptcTags) as $key) {
            $tag = \str_replace('2#', '', $key);
            $iptc .= $this->iptcMakeTag(2, (int) $tag, $this->parsedIptcTags[$key]);
        }

        return $iptc;
    }

    /**
     * Assemble the tags "IPTC" in character "ascii".
     *
     * @param int   $rec - Type of tag ex. 2
     * @param int   $dat - code of tag ex. 025 or 000 etc
     * @param mixed $val - any caracterer
     *
     * @return binary source
     */

    /**
     * @param int      $typeOfTag   - type of tag ex. 2
     * @param int      $codeOfTag   - code of tag ex. 025 or 000 etc, look at class' constants
     * @param string[] $valuesOfTag - values of the tag
     *
     * @return string
     */
    public function iptcMakeTag(int $typeOfTag, int $codeOfTag, array $valuesOfTag): string
    {
        //beginning of the binary string
        $beginningOfTheBinaryString = \chr(0x1c)
            .\chr($typeOfTag)
            .\chr($codeOfTag);

        $binaryString = '';
        foreach ($valuesOfTag as $value) {
            $lengthOfValue = \strlen($value);
            $binaryString .= $beginningOfTheBinaryString
                .$this->testBitSize($lengthOfValue)
                .$value;
        }

        return $binaryString;
    }

    /**
     * Creates the new image file with the new "IPTC" recorded.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function write(): bool
    {
        //@see http://php.net/manual/pt_BR/function.iptcembed.php
        $content = \iptcembed($this->binary(), $this->pathToFile, 0);
        if (false === $content) {
            throw new \Exception('Failed to save IPTC data into file');
        }

        @unlink($this->pathToFile);

        return false !== \file_put_contents($this->pathToFile, $content);
    }

    /**
     * Completely remove all tags "IPTC" image.
     * Basically creates a new image with no IPTC tags.
     */
    public function removeAllTags()
    {
        $this->hasIptcTags = false;
        $this->parsedIptcTags = [];
        $impl = \implode(\file($this->pathToFile));
        $img = \imagecreatefromstring($impl);
        \unlink($this->pathToFile);
        \imagejpeg($img, $this->pathToFile, 100);
    }

    /**
     * It proper test to ensure that
     * the size of the values are supported within the.
     *
     * @param int $lengthOfTheCharacter - size of the character
     *
     * @return string
     */
    private function testBitSize($lengthOfTheCharacter): string
    {
        if ($lengthOfTheCharacter < 0x8000) {
            return
                chr($lengthOfTheCharacter >> 8).
                chr($lengthOfTheCharacter & 0xff);
        }

        return
            chr(0x1c).chr(0x04).
            chr(($lengthOfTheCharacter >> 24) & 0xff).
            chr(($lengthOfTheCharacter >> 16) & 0xff).
            chr(($lengthOfTheCharacter >> 8) & 0xff).
            chr(($lengthOfTheCharacter) & 0xff);
    }

    /**
     * Decode charset utf8 before being saved.
     *
     * @param array $data
     *
     * @return array
     */
    private function decode(array $data): array
    {
        $result = array();

        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data)) as $value) {
            $result[] = \utf8_decode($value);
        }

        return $result;
    }

    /**
     * Encode charset to utf8 before being saved.
     *
     * @param array $data
     *
     * @return array
     */
    private function encode(array $data): array
    {
        $result = array();

        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data)) as $value) {
            $result[] = \utf8_encode($value);
        }

        return $result;
    }

    /**
     * @param string $tag
     *
     * @return string
     */
    private function buildTagName(string $tag): string
    {
        return '2#'.$tag;
    }
}
