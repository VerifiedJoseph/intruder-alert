<?php

namespace Helper;

use JsonException;
use Exception\AppException;

/**
 * Class for encoding and decoding JSON
 */
final class Json
{
    /**
     * Encode JSON
     *
     * @param array<mixed> $data
     * @return string
     *
     * @throws AppException if array could not be encoded
     */
    public static function encode(array $data): string
    {
        try {
            return json_encode($data, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $err) {
            throw new AppException('JSON Error: ' . $err->getMessage());
        }
    }

    /**
     * Decode JSON
     *
     * @param string $json
     * @return array<mixed>
     *
     * @throws AppException if JSON could not be decoded
     */
    public static function decode(string $json): array
    {
        try {
            return json_decode($json, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $err) {
            throw new AppException('JSON Error: ' . $err->getMessage());
        }
    }
}
