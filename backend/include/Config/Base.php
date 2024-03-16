<?php

namespace IntruderAlert\Config;

abstract class Base
{
    protected string $envPrefix = 'IA_';

    /**
     * Check if a environment variable is a boolean
     *
     * @param string $name Variable name excluding prefix
     */
    protected function isEnvBoolean(string $name): bool
    {
        return in_array($this->getEnv($name), ['true', 'false']);
    }

    /**
     * Check for an environment variable
     *
     * @param string $name Variable name excluding prefix
     */
    protected function hasEnv(string $name): bool
    {
        if (getenv($this->envPrefix . $name) === false) {
            return false;
        }

        return true;
    }

    /**
     * Get an environment variable
     *
     * @param string $name Variable name excluding prefix
     */
    protected function getEnv(string $name): mixed
    {
        return getenv($this->envPrefix . $name);
    }
}
