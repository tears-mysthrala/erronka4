<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Core;

/**
 * Native PSR-4 ClassLoader
 * Replaces Composer's autoloader for Zero Trust environments.
 */
class ClassLoader
{
    /**
     * Register the autoloader.
     */
    public static function register(): void
    {
        spl_autoload_register(function ($class) {
            // Project-specific namespace prefix
            $prefix = 'ZabalaGailetak\\HrPortal\\';

            // Base directory for the namespace prefix
            $base_dir = dirname(__DIR__) . '/';

            // Does the class use the namespace prefix?
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                // no, move to the next registered autoloader
                return;
            }

            // Get the relative class name
            $relative_class = substr($class, $len);

            // Replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // If the file exists, require it
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}
