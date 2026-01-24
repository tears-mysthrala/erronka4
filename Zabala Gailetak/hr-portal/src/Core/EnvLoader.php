<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Core;

use Exception;

/**
 * Native EnvLoader Parser
 * Replaces vlucas/phpdotenv for Zero Trust environments.
 */
class EnvLoader
{
    /**
     * Load environment variables from .env file.
     *
     * @param string $path Path to directory containing .env file
     */
    public static function load(string $path): void
    {
        if (!file_exists($path . '/.env')) {
            // If .env doesn't exist, check for .env.example or ignore (production might use real env vars)
            if (file_exists($path . '/.env.example')) {
                // Optional: warn or fallback? For now, silent.
            }
            return;
        }

        $lines = file($path . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Trim whitespace
            $line = trim($line);

            // Skip comments and empty lines
            if (str_starts_with($line, '#') || empty($line)) {
                continue;
            }

            // Split by the first '='
            $parts = explode('=', $line, 2);

            if (count($parts) !== 2) {
                continue;
            }

            $name = trim($parts[0]);
            $value = trim($parts[1]);

            // Remove quotes if present
            if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                $value = substr($value, 1, -1);
            } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                $value = substr($value, 1, -1);
            }

            // Set environment variables
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
