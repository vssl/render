<?php

namespace Tests\Mocks;

use DateInterval;
use Psr\SimpleCache\CacheInterface;

/**
 * A minimal in-memory PSR-16 cache used in place of a real cache backend for tests.
 */
class ArrayCache implements CacheInterface
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $expirations = [];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->values[$key] : $default;
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool
    {
        $this->values[$key] = $value;
        $this->expirations[$key] = is_int($ttl) ? time() + $ttl : null;
        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->values[$key], $this->expirations[$key]);
        return true;
    }

    public function clear(): bool
    {
        $this->values = [];
        $this->expirations = [];
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->get($key, $default);
        }
        return $results;
    }

    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has(string $key): bool
    {
        if (!array_key_exists($key, $this->values)) {
            return false;
        }

        $expiration = $this->expirations[$key] ?? null;
        if ($expiration !== null && $expiration <= time()) {
            $this->delete($key);
            return false;
        }

        return true;
    }
}
