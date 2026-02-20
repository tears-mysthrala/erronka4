<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Database;

use PDO;
use PDOException;

/**
 * Database Connection Manager
 *
 * Manages PostgreSQL database connection with connection pooling
 */
class Database
{
    private static ?PDO $connection = null;
    private array $config;

    public function __construct()
    {
        // phpcs:disable
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', dirname(__DIR__, 2));
        }
        // phpcs:enable
        $this->config = require ROOT_PATH . '/config/config.php';
    }

    /**
     * Get database connection (singleton pattern)
     */
    public function getConnection(): PDO
    {
        if (self::$connection === null) {
            $this->connect();
        }

        return self::$connection;
    }

    /**
     * Establish database connection
     */
    private function connect(): void
    {
        $dbConfig = $this->config['database'];

        // Safe Env Getter (duplicated for robustness in low-level class)
        $env = function ($key, $default = null) {
            return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
        };

        $driver = $dbConfig['driver'] ?? $env('DB_DRIVER', 'pgsql');
        $host = $dbConfig['host'] ?? $env('DB_HOST', 'localhost');
        $port = $dbConfig['port'] ?? $env('DB_PORT', 5432);
        $dbname = $dbConfig['database'] ?? $env('DB_NAME', 'hr_portal');
        $user = $dbConfig['username'] ?? $env('DB_USER', 'hr_user');
        $pass = $dbConfig['password'] ?? $env('DB_PASSWORD', '');

        if ($driver === 'pgsql') {
            $dsn = sprintf(
                'pgsql:host=%s;port=%d;dbname=%s',
                $host,
                $port,
                $dbname
            );
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $host,
                $port ?? 3306,
                $dbname
            );
        }

        try {
            // Default options optimized for InfinityFree shared hosting
            // ATTR_PERSISTENT: Reuse connections to avoid max_user_connections limit
            $defaultOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true, // Critical for InfinityFree's connection limits
            ];
            
            self::$connection = new PDO(
                $dsn,
                $user,
                $pass,
                array_merge($defaultOptions, $dbConfig['options'] ?? [])
            );
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new \RuntimeException('Database connection failed');
        }
    }

    /**
     * Execute a query
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * Prepare a statement
     */
    public function prepare(string $sql): \PDOStatement
    {
        return $this->getConnection()->prepare($sql);
    }

    /**
     * Begin transaction
     */
    public function beginTransaction(): void
    {
        $this->getConnection()->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit(): void
    {
        $this->getConnection()->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): void
    {
        $this->getConnection()->rollBack();
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId(?string $name = null): string
    {
        return $this->getConnection()->lastInsertId($name);
    }

    /**
     * Get the current database driver
     */
    public function getDriver(): string
    {
        return $this->getConnection()->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Check if using PostgreSQL
     */
    public function isPostgreSQL(): bool
    {
        return $this->getDriver() === 'pgsql';
    }

    /**
     * Check if using MySQL/MariaDB
     */
    public function isMySQL(): bool
    {
        return $this->getDriver() === 'mysql';
    }

    /**
     * Get case-insensitive LIKE operator for current database
     */
    public function getLikeOperator(): string
    {
        // PostgreSQL has ILIKE, MySQL's LIKE is case-insensitive by default (depending on collation)
        return $this->isPostgreSQL() ? 'ILIKE' : 'LIKE';
    }

    /**
     * Get concat expression for current database
     * 
     * @param array<string> $columns
     */
    public function concat(array $columns): string
    {
        if ($this->isPostgreSQL()) {
            return implode(' || ', $columns);
        }
        return 'CONCAT(' . implode(', ', $columns) . ')';
    }

    /**
     * Get date add expression for current database
     */
    public function dateAdd(string $date, string $interval, string $unit = 'days'): string
    {
        if ($this->isPostgreSQL()) {
            return "{$date} + INTERVAL '{$interval} {$unit}'";
        }
        // MySQL/MariaDB
        return "DATE_ADD({$date}, INTERVAL {$interval} {$unit})";
    }

    /**
     * Get current date expression
     */
    public function currentDate(): string
    {
        return 'CURRENT_DATE';
    }

    /**
     * Get year extraction from date
     */
    public function extractYear(string $column): string
    {
        if ($this->isPostgreSQL()) {
            return "EXTRACT(YEAR FROM {$column})";
        }
        return "YEAR({$column})";
    }

    /**
     * Get month extraction from date
     */
    public function extractMonth(string $column): string
    {
        if ($this->isPostgreSQL()) {
            return "EXTRACT(MONTH FROM {$column})";
        }
        return "MONTH({$column})";
    }
}
