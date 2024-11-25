<?php

namespace IntruderAlert;

use IntruderAlert\Exception\FetchException;

class Fetch
{
    /** @var string $username HTTP request useragent */
    private string $useragent = '';

    private Logger $logger;

    /**
     * @param string $useragent HTTP request useragent
     */
    public function __construct(string $useragent, Logger $logger)
    {
        $this->useragent = $useragent;
        $this->logger = $logger;
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
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => $this->useragent
        ]);

        $this->logger->debug('GET request: ' . $url);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME) . 's';
        $downloadSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD) . ' bytes';

        curl_close($ch);

        if ($error !== '') {
            throw new FetchException($error);
        }

        $this->logger->debug('Status code: ' . $statusCode);
        $this->logger->debug('Effective URL: ' . $effectiveUrl);
        $this->logger->debug('Download size: ' . $downloadSize);
        $this->logger->debug('Total time: ' . $totalTime);

        if ($statusCode !== 200) {
            throw new FetchException(sprintf(
                'Request failed. Returned HTTP %s',
                $statusCode
            ));
        }

        return (string) $response;
    }
}
