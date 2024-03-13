<?php

namespace IntruderAlert\Database\Updater;

/**
 * Class for creating MaxMind download URLs
 */
class Url
{
    private string $url;
    private string $key;

    /**
     * @param $url Base MaxMind download URL
     * @param $key MaxMind license key
     */
    public function __construct(string $url, string $key)
    {
        $this->url = $url;
        $this->key = $key;
    }

    /**
     * Get full MaxMind download URL with query containing database edition and suffix
     *
     * @param string $edition Database edition
     * @param string $suffix Suffix
     * @return string
     */
    public function get(string $edition, $suffix): string
    {
        $parts = [
            'edition_id' => $edition,
            'license_key' => $this->key,
            'suffix' => $suffix
        ];

        return $this->url . http_build_query($parts);
    }
}
