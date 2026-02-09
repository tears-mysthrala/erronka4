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
            self::$connection = new PDO(
                $dsn,
                $user,
                $pass,
                $dbConfig['options'] ?? [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
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
}
