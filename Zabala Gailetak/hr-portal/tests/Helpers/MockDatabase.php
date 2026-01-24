<?php

declare(strict_types=1);

namespace Tests\Helpers;

use PDO;
use PDOStatement;

/**
 * Mock Database for testing
 * Provides a simple in-memory database mock
 */
class MockDatabase
{
    private array $data = [];
    private array $lastQuery = [];

    /**
     * Simulate query execution
     */
    public function query(string $sql, array $params = []): PDOStatement|false
    {
        $this->lastQuery = ['sql' => $sql, 'params' => $params];

        // Return mock PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([]);

        return $stmt;
    }

    /**
     * Prepare a statement
     */
    public function prepare(string $sql): PDOStatement|false
    {
        $this->lastQuery = ['sql' => $sql];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([]);
        $stmt->method('fetch')->willReturn(false);

        return $stmt;
    }

    /**
     * Execute a prepared statement
     */
    public function execute(PDOStatement $stmt, array $params = []): array
    {
        // Return mock data based on the query
        return [];
    }

    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return true;
    }

    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return true;
    }

    /**
     * Rollback transaction
     */
    public function rollBack(): bool
    {
        return true;
    }

    /**
     * Get last query for debugging
     */
    public function getLastQuery(): array
    {
        return $this->lastQuery;
    }

    /**
     * Set mock data for testing
     */
    public function setMockData(string $table, array $data): void
    {
        $this->data[$table] = $data;
    }

    /**
     * Get mock data
     */
    public function getMockData(string $table): array
    {
        return $this->data[$table] ?? [];
    }

    /**
     * Helper to create mock objects
     */
    private function createMock(string $className): object
    {
        return new class {
            private array $methods = [];

            public function __call(string $name, array $arguments)
            {
                if (isset($this->methods[$name])) {
                    return call_user_func_array($this->methods[$name], $arguments);
                }
                return $this;
            }

            public function method(string $name): self
            {
                return $this;
            }

            public function willReturn($value): self
            {
                return $this;
            }
        };
    }
}
