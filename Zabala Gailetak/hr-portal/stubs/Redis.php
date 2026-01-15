<?php

/**
 * Redis stub file for IDE support
 * This file provides type hints for the Redis PHP extension
 * @link https://github.com/phpredis/phpredis
 */

class Redis
{
    /**
     * @return bool
     */
    public function connect(string $host, int $port = 6379, float $timeout = 0, string $persistent_id = null, int $retry_interval = 0): bool { return true; }

    /**
     * @return bool
     */
    public function pconnect(string $host, int $port = 6379, float $timeout = 0, string $persistent_id = null): bool { return true; }

    /**
     * @return bool
     */
    public function auth(string $password): bool { return true; }

    /**
     * @return bool
     */
    public function select(int $db): bool { return true; }

    /**
     * @return bool|string
     */
    public function get(string $key) { return ''; }

    /**
     * @return bool
     */
    public function set(string $key, mixed $value, mixed $timeout = null): bool { return true; }

    /**
     * @return bool
     */
    public function setex(string $key, int $ttl, mixed $value): bool { return true; }

    /**
     * @return int
     */
    public function del(string $key, string ...$other_keys): int { return 0; }

    /**
     * @return bool
     */
    public function exists(string $key): bool { return false; }

    /**
     * @return bool
     */
    public function expire(string $key, int $ttl): bool { return true; }

    /**
     * @return int
     */
    public function ttl(string $key): int { return 0; }

    /**
     * @return array
     */
    public function keys(string $pattern): array { return []; }

    /**
     * Scan keys with iterator (preferred over keys() for production)
     * @param int $iterator Reference to iterator
     * @param string $pattern Pattern to match
     * @param int $count Number of elements to return per iteration
     * @return array|false
     */
    public function scan(&$iterator, string $pattern = null, int $count = 0) { return []; }

    /**
     * @return bool
     */
    public function flushDB(): bool { return true; }

    /**
     * @return bool
     */
    public function flushAll(): bool { return true; }

    /**
     * @return int
     */
    public function incr(string $key): int { return 0; }

    /**
     * @return int
     */
    public function incrBy(string $key, int $value): int { return 0; }

    /**
     * @return int
     */
    public function decr(string $key): int { return 0; }

    /**
     * @return int
     */
    public function decrBy(string $key, int $value): int { return 0; }

    /**
     * @return bool
     */
    public function hSet(string $key, string $hashKey, string $value): bool { return true; }

    /**
     * @return string|false
     */
    public function hGet(string $key, string $hashKey) { return ''; }

    /**
     * @return array
     */
    public function hGetAll(string $key): array { return []; }

    /**
     * @return int
     */
    public function hDel(string $key, string $hashKey1, string ...$otherHashKeys): int { return 0; }

    /**
     * @return bool
     */
    public function hExists(string $key, string $hashKey): bool { return false; }

    /**
     * @return array
     */
    public function hKeys(string $key): array { return []; }

    /**
     * @return array
     */
    public function hVals(string $key): array { return []; }

    /**
     * @return int
     */
    public function hLen(string $key): int { return 0; }

    /**
     * @return int
     */
    public function lPush(string $key, string $value1, string ...$otherValues): int { return 0; }

    /**
     * @return int
     */
    public function rPush(string $key, string $value1, string ...$otherValues): int { return 0; }

    /**
     * @return string|false
     */
    public function lPop(string $key) { return ''; }

    /**
     * @return string|false
     */
    public function rPop(string $key) { return ''; }

    /**
     * @return int
     */
    public function lLen(string $key): int { return 0; }

    /**
     * @return array
     */
    public function lRange(string $key, int $start, int $end): array { return []; }

    /**
     * @return int
     */
    public function sAdd(string $key, string $value1, string ...$otherValues): int { return 0; }

    /**
     * @return int
     */
    public function sRem(string $key, string $member1, string ...$otherMembers): int { return 0; }

    /**
     * @return array
     */
    public function sMembers(string $key): array { return []; }

    /**
     * @return bool
     */
    public function sIsMember(string $key, string $value): bool { return false; }

    /**
     * @return int
     */
    public function sCard(string $key): int { return 0; }

    /**
     * @return int
     */
    public function zAdd(string $key, array $options = null, mixed $score1, mixed $value1 = null, mixed $scoreN = null, mixed $valueN = null): int { return 0; }

    /**
     * @return int
     */
    public function zRem(string $key, string $member1, string ...$otherMembers): int { return 0; }

    /**
     * @return array
     */
    public function zRange(string $key, int $start, int $end, bool $withscores = false): array { return []; }

    /**
     * @return array
     */
    public function zRevRange(string $key, int $start, int $end, bool $withscores = false): array { return []; }

    /**
     * @return int
     */
    public function zCard(string $key): int { return 0; }

    /**
     * @return float|false
     */
    public function zScore(string $key, string $member) { return 0.0; }

    /**
     * @return bool
     */
    public function multi(int $mode = \Redis::MULTI): bool { return true; }

    /**
     * @return array
     */
    public function exec(): array { return []; }

    /**
     * @return bool
     */
    public function discard(): bool { return true; }

    /**
     * @return bool
     */
    public function watch(string $key, string ...$other_keys): bool { return true; }

    /**
     * @return bool
     */
    public function unwatch(): bool { return true; }

    /**
     * @return string|false
     */
    public function ping(): string { return 'PONG'; }

    /**
     * @return bool
     */
    public function close(): bool { return true; }

    /**
     * @return string
     */
    public function getLastError(): string { return ''; }

    /**
     * @return bool
     */
    public function clearLastError(): bool { return true; }

    const ATOMIC = 0;
    const MULTI = 1;
    const PIPELINE = 2;

    const OPT_SERIALIZER = 1;
    const OPT_PREFIX = 2;
    const OPT_READ_TIMEOUT = 3;
    const OPT_SCAN = 4;
    const OPT_SLAVE_FAILOVER = 5;

    const SERIALIZER_NONE = 0;
    const SERIALIZER_PHP = 1;
    const SERIALIZER_IGBINARY = 2;
    const SERIALIZER_MSGPACK = 3;
    const SERIALIZER_JSON = 4;

    const AFTER = 'after';
    const BEFORE = 'before';
}
