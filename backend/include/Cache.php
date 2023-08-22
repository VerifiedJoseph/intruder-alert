<?php

use Helper\File;
use Helper\Json;

final class Cache
{
    /**
     * @var int $lifetime Number of hours to cache data in seconds.
     */
    private int $lifetime = 43200;

    /**
     * @var string $path Cache folder path
     */
    private string $path = '';

    /**
     * @var array<string, mixed> $data Data from cache file
     */
    private array $data = [
        'expires' => 0,
        'items' => []
    ];

    /**
     * Constructor
     *
     * @param string $path Cache folder path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->load();

        if ($this->hasExpired() === true) {
           $this->reset();
        }
    }

    /**
     * Get item
     * 
     * @param string $ipAddress IP address
     * @return array<mixed>
     */
    public function getItem(string $ipAddress): array
    {
        if ($this->hasItem($ipAddress) === true) {
            return $this->data['items'][$ipAddress];
        }

        return [];
    }

    /**
     * Check cache for IP address
     * 
     * @param string $ipAddress IP address
     */
    public function hasItem(string $ipAddress): bool
    {
        if (array_key_exists($ipAddress, $this->data['items']) === true) {
            return true;
        }

        return false;
    }

    /**
     * Add item to cache
     * 
     * @param array<string, mixed> $ip IP address details
     */
    public function addItem(array $ip): void
    {
        $this->data['items'][$ip['address']] = [
            'address' => $ip['address'],
            'version' => $ip['version'],
            'network' => $ip['network'],
            'country' => $ip['country'],
            'continent' => $ip['continent'],
        ];
    }

    /**
     * Save cache data to disk
     */
    public function save(): void
    {
        $this->data['expires'] = time() + $this->lifetime;

		File::write(
			$this->getPath(),
			Json::encode($this->data)
		);
    }

    /**
     * Has the cache expired
     *
     * @return bool
     */
    private function hasExpired(): bool
    {
        if (time() >= $this->data['expires']) {
            return true;
        }

        return false;
    }

    /**
     * Reset cache
     */
    private function reset(): void
    {
        $this->data = [
            'expires' => 0,
            'items' => []
        ];
    }

    /**
     * Load and decode cache file
     */
    private function load(): void
    {
        if (File::exists($this->getPath()) === true) {
            $json = File::read($this->getPath());
            $this->data = Json::decode($json);
        }
    }

    /**
     * Get cache file path
     *
     * @return string
     */
    private function getPath(): string
    {
        return $this->path;
    }
}