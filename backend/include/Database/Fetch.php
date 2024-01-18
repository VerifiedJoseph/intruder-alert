<?php

namespace IntruderAlert\Database;

use IntruderAlert\Exception\FetchException;

class Fetch
{
    /** @var string $username HTTP request useragent */
    private string $useragent = '';

    /**
     * @param string $useragent HTTP request useragent
     */
    public function __construct(string $useragent)
    {
        $this->useragent = $useragent;
    }

    /**
     * Make a GET request
     *
     * @param string $url Request URL
     * @return string $data Response body
     *
     * @throws FetchException if an CURL error occurs
     * @throws FetchException if request returns non-200 status code
     */
    public function get(string $url)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->useragent
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error !== '') {
            throw new FetchException($error);
        }

        if ($statusCode !== 200) {
            throw new FetchException(sprintf(
                'Request failed. Returned HTTP %s',
                $statusCode
            ));
        }

        return (string) $response;
    }
}
