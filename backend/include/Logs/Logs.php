<?php

declare(strict_types=1);

namespace IntruderAlert\Logs;

use IntruderAlert\Config;
use IntruderAlert\Logger;
use IntruderAlert\Helper\Timer;
use IntruderAlert\Helper\Convert;
use IntruderAlert\Exception\AppException;
use IntruderAlert\Exception\LogsException;
use SplFileInfo;
use RegexIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class for processing Fail2ban logs
 */
class Logs
{
    /** @var Config Config class instance */
    private Config $config;

    /** @var Logger Logger class instance */
    private Logger $logger;

    /** @var string $filenameRegex Log filename regex */
    private $filenameRegex = '/fail2ban\.log/';

    /**
     * @param Config $config Config class instance
     */
    public function __construct(Config $config, Logger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Process logs
     *
     * @return array<int, array<string, string>>
     */
    public function process(): array
    {
        $rows = [];

        foreach ($this->getFiles() as $file) {
            $timer = new Timer();
            $timer->start();

            $this->logger->info('Processing ' . $file->getPathname());

            if ($file->isReadable() === false) {
                throw new AppException('Failed to read file: ' . $file->getPathname());
            }

            if ($file->getSize() === 0) {
                $this->logger->info('File is empty. Skipping ' . $file->getPathname());
                continue;
            }

            $lineCount = 0;
            $banCount = 0;

            $fp = @gzopen($file->getPathname(), 'r');

            if ($fp === false) {
                throw new AppException('Failed to open file: ' . $file->getPathname());
            }

            while ($current = fgets($fp)) {
                $line = new LineExtractor($current);
                $lineCount += 1;

                $timestamp = Convert::timezone(
                    $line->getTimestamp(),
                    $this->config->getSystemLogTimezone(),
                    $this->config->getTimezone()
                );

                if ($line->hasBan() === true) {
                    $banCount += 1;
                    $rows[] = [
                        'ip' => $line->getIp(),
                        'jail' => $line->getJail(),
                        'timestamp' => $timestamp
                    ];
                }
            }

            $timer->stop();

            $message = sprintf(
                'Scanned %s lines and found %s bans in %s (%ss)',
                number_format($lineCount),
                number_format($banCount),
                $file->getPathname(),
                $timer->getTime()
            );

            $this->logger->info($message);
        }

        if (count($rows) === 0) {
            throw new LogsException('No bans found');
        }

        $totalBans = number_format(count($rows));
        $this->logger->info(sprintf('Found %s bans in all files.', $totalBans));

        return $rows;
    }

    /**
     * Get logs file
     *
     * @return array<int, SplFileInfo>
     */
    private function getFiles(): array
    {
        if ($this->config->getLogPaths() !== '') {
            $paths = array_unique(explode(',', $this->config->getLogPaths()));
            $files = [];

            foreach ($paths as $path) {
                $files[] = new SplFileInfo($path);
            }

            return $files;
        }

        $directory = new RecursiveDirectoryIterator($this->config->getLogFolder());
        $flattened = new RecursiveIteratorIterator($directory);

        $files = [];
        foreach (new RegexIterator($flattened, $this->filenameRegex) as $file) {
            $files[] = $file;
        }

        return $files;
    }
}
