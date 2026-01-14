<?php

declare(strict_types=1);

namespace HrPortal\Database;

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
        
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['database']
        );
        
        try {
            self::$connection = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['options']
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
